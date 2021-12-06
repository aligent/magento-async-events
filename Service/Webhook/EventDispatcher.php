<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Api\AsyncEventRepositoryInterface;
use Aligent\Webhooks\Helper\NotifierResult;
use Aligent\Webhooks\Model\AsyncEvent;
use Aligent\Webhooks\Model\WebhookLogFactory;
use Aligent\Webhooks\Model\WebhookLogRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\AlreadyExistsException;

class EventDispatcher
{
    /**
     * @var AsyncEventRepositoryInterface
     */
    private $webhookRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var NotifierFactoryInterface
     */
    private $notifierFactory;

    /**
     * @var WebhookLogRepository
     */
    private $webhookLogRepository;

    /**
     * @var WebhookLogFactory
     */
    private $webhookLogFactory;

    /**
     * @var RetryManager
     */
    private $retryManager;

    /**
     * @param AsyncEventRepositoryInterface $webhookRepository
     * @param WebhookLogRepository $webhookLogRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param NotifierFactoryInterface $notifierFactory
     * @param WebhookLogFactory $webhookLogFactory
     * @param RetryManager $retryManager
     */
    public function __construct(
        AsyncEventRepositoryInterface $webhookRepository,
        WebhookLogRepository          $webhookLogRepository,
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        NotifierFactoryInterface      $notifierFactory,
        WebhookLogFactory             $webhookLogFactory,
        RetryManager                  $retryManager
    ) {
        $this->webhookRepository = $webhookRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->notifierFactory = $notifierFactory;
        $this->webhookLogRepository = $webhookLogRepository;
        $this->webhookLogFactory = $webhookLogFactory;
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

        $webhooks = $this->webhookRepository->getList($searchCriteria)->getItems();

        /** @var AsyncEvent $webhook */
        foreach ($webhooks as $webhook) {
            $handler = $webhook->getMetadata();

            $notifier = $this->notifierFactory->create($handler);

            $response = $notifier->notify($webhook, [
                'data' => $output
            ]);

            $this->log($response);

            if (!$response->getSuccess()) {
                $this->retryManager->init($webhook->getSubscriptionId(), $output);
            }
        }
    }

    /**
     * @param NotifierResult $response
     * @return void
     */
    private function log(NotifierResult $response)
    {
        $webhookLog = $this->webhookLogFactory->create();
        $webhookLog->setSuccess($response->getSuccess());
        $webhookLog->setSubscriptionId($response->getSubscriptionId());
        $webhookLog->setResponseData($response->getResponseData());

        try {
            $this->webhookLogRepository->save($webhookLog);
        } catch (AlreadyExistsException $exception) {
            // Do nothing because a log entry can never already exist
        }
    }

}
