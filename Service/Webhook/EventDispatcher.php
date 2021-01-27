<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Aligent\Webhooks\Model\WebhookLogFactory;
use Aligent\Webhooks\Model\WebhookLogRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

class EventDispatcher
{
    /**
     * @var WebhookRepositoryInterface
     */
    private WebhookRepositoryInterface $webhookRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var NotifierFactoryInterface
     */
    private NotifierFactoryInterface $notifierFactory;

    /**
     * @var WebhookLogRepository
     */
    private WebhookLogRepository $webhookLogRepository;

    /**
     * @var WebhookLogFactory
     */
    private WebhookLogFactory $webhookLogFactory;

    public function __construct(
        WebhookRepositoryInterface $webhookRepository,
        WebhookLogRepository $webhookLogRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        NotifierFactoryInterface $notifierFactory,
        WebhookLogFactory $webhookLogFactory
    ) {
        $this->webhookRepository = $webhookRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->notifierFactory = $notifierFactory;
        $this->webhookLogRepository = $webhookLogRepository;
        $this->webhookLogFactory = $webhookLogFactory;
    }

    /**
     * @param string $eventName
     * @param string $objectId
     * @return \Aligent\Webhooks\Service\Webhook\NotifierInterface[]
     */
    public function loadSubscribers(string $eventName, string $objectId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', 1)
            ->addFilter('event_name', $eventName)
            ->create();

        $webhooks = $this->webhookRepository->getList($searchCriteria)->getItems();

        /** @var \Aligent\Webhooks\Service\Webhook\NotifierInterface[] $subscribers */
        $subscribers = [];

        /** @var \Aligent\Webhooks\Model\Webhook $webhook */
        foreach ($webhooks as $webhook) {
            $subscribers[] = $this->notifierFactory->create($webhook, $objectId);
        }

        return $subscribers;
    }

    /**
     * @param \Aligent\Webhooks\Service\Webhook\NotifierInterface[] $subscribers
     */
    public function dispatch(array $subscribers)
    {
        foreach ($subscribers as $subscriber) {
            $response = $subscriber->notify();

            $webhookLog = $this->webhookLogFactory->create();
            $webhookLog->setSuccess($response->getResult());
            $webhookLog->setSubscriptionId($response->getMetadata());

            $this->webhookLogRepository->save($webhookLog);
        }
    }
}
