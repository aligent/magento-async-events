<?php

namespace Aligent\AsyncEvents\Service\AsyncEvent;

use Aligent\AsyncEvents\Api\AsyncEventRepositoryInterface;
use Aligent\AsyncEvents\Helper\NotifierResult;
use Aligent\AsyncEvents\Model\AsyncEvent;
use Aligent\AsyncEvents\Model\AsyncEventLogFactory;
use Aligent\AsyncEvents\Model\AsyncEventLogRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\AlreadyExistsException;
use Ramsey\Uuid\Uuid;

class EventDispatcher
{
    /**
     * @var AsyncEventRepositoryInterface
     */
    private $asyncEventRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var NotifierFactoryInterface
     */
    private $notifierFactory;

    /**
     * @var AsyncEventLogRepository
     */
    private $asyncEventLogRepository;

    /**
     * @var AsyncEventLogFactory
     */
    private $asyncEventLogFactory;

    /**
     * @var RetryManager
     */
    private $retryManager;

    /**
     * @param AsyncEventRepositoryInterface $asyncEventRepository
     * @param AsyncEventLogRepository $asyncEventLogRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param NotifierFactoryInterface $notifierFactory
     * @param AsyncEventLogFactory $asyncEventLogFactory
     * @param RetryManager $retryManager
     */
    public function __construct(
        AsyncEventRepositoryInterface $asyncEventRepository,
        AsyncEventLogRepository       $asyncEventLogRepository,
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        NotifierFactoryInterface      $notifierFactory,
        AsyncEventLogFactory          $asyncEventLogFactory,
        RetryManager                  $retryManager
    ) {
        $this->asyncEventRepository = $asyncEventRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->notifierFactory = $notifierFactory;
        $this->asyncEventLogRepository = $asyncEventLogRepository;
        $this->asyncEventLogFactory = $asyncEventLogFactory;
        $this->retryManager = $retryManager;
    }

    /**
     * @param string $eventName
     * @param mixed $output
     */
    public function dispatch(string $eventName, $output)
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

            $response = $notifier->notify($asyncEvent, [
                'data' => $output
            ]);

            $uuid = Uuid::uuid4()->toString();
            $response->setUuid($uuid);

            $this->log($response);

            if (!$response->getSuccess()) {
                $this->retryManager->init($asyncEvent->getSubscriptionId(), $output, $uuid);
            }
        }
    }

    /**
     * @param NotifierResult $response
     * @return void
     */
    private function log(NotifierResult $response)
    {
        $asyncEventLog = $this->asyncEventLogFactory->create();
        $asyncEventLog->setSuccess($response->getSuccess());
        $asyncEventLog->setSubscriptionId($response->getSubscriptionId());
        $asyncEventLog->setResponseData($response->getResponseData());
        $asyncEventLog->setUuid($response->getUuid());

        try {
            $this->asyncEventLogRepository->save($asyncEventLog);
        } catch (AlreadyExistsException $exception) {
            // Do nothing because a log entry can never already exist
        }
    }

}
