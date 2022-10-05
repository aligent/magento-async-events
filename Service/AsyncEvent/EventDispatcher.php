<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Service\AsyncEvent;

use Aligent\AsyncEvents\Api\AsyncEventRepositoryInterface;
use Aligent\AsyncEvents\Helper\NotifierResult;
use Aligent\AsyncEvents\Model\AsyncEvent;
use Aligent\AsyncEvents\Model\AsyncEventLog;
use Aligent\AsyncEvents\Model\AsyncEventLogFactory;
use Aligent\AsyncEvents\Model\AsyncEventLogRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject\IdentityGeneratorInterface;
use Magento\Framework\Exception\AlreadyExistsException;

class EventDispatcher
{
    /**
     * @param AsyncEventRepositoryInterface $asyncEventRepository
     * @param AsyncEventLogRepository $asyncEventLogRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param NotifierFactoryInterface $notifierFactory
     * @param AsyncEventLogFactory $asyncEventLogFactory
     * @param IdentityGeneratorInterface $identityService
     * @param RetryManager $retryManager
     */
    public function __construct(
        private readonly AsyncEventRepositoryInterface $asyncEventRepository,
        private readonly AsyncEventLogRepository $asyncEventLogRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly NotifierFactoryInterface $notifierFactory,
        private readonly AsyncEventLogFactory $asyncEventLogFactory,
        private readonly IdentityGeneratorInterface $identityService,
        private readonly RetryManager $retryManager
    ) {
    }

    /**
     * Dispatch an asynchronous event to all subscribers
     *
     * @param string $eventName
     * @param mixed $output
     * @return void
     */
    public function dispatch(string $eventName, mixed $output): void
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', 1)
            ->addFilter('event_name', $eventName)
            ->create();

        $asyncEvents = $this->asyncEventRepository->getList($searchCriteria)->getItems();

        /** @var AsyncEvent $asyncEvent */
        foreach ($asyncEvents as $asyncEvent) {
            $handler = $asyncEvent->getMetadata();

            $notifier = $this->notifierFactory->create($handler);

            $response = $notifier->notify(
                $asyncEvent,
                [
                    'data' => $output
                ]
            );

            $uuid = $this->identityService->generateId();
            $response->setUuid($uuid);

            $this->log($response);

            if (!$response->getSuccess()) {
                $this->retryManager->init($asyncEvent->getSubscriptionId(), $output, $uuid);
            }
        }
    }

    /**
     * Log the initial asynchronous event dispatch
     *
     * @param NotifierResult $response
     * @return void
     */
    private function log(NotifierResult $response): void
    {
        /** @var AsyncEventLog $asyncEventLog */
        $asyncEventLog = $this->asyncEventLogFactory->create();
        $asyncEventLog->setSuccess($response->getSuccess());
        $asyncEventLog->setSubscriptionId($response->getSubscriptionId());
        $asyncEventLog->setResponseData($response->getResponseData());
        $asyncEventLog->setUuid($response->getUuid());
        $asyncEventLog->setSerializedData($response->getAsyncEventData());

        try {
            $this->asyncEventLogRepository->save($asyncEventLog);
        } catch (AlreadyExistsException) {
            return;
        }
    }
}
