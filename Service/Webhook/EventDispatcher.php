<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class EventDispatcher
{
    /**
     * A collection of listeners listening to this event
     * @var Notifier[]
     */
    private array $subscribers = [];

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

    public function __construct(
        WebhookRepositoryInterface $webhookRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        NotifierFactoryInterface $notifierFactory
    ) {
        $this->webhookRepository = $webhookRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->notifierFactory = $notifierFactory;
    }

    public function loadSubscribers(string $eventName)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', 1)
            ->addFilter('event_name', $eventName)
            ->create();

        $results = $this->webhookRepository->getList($searchCriteria)->getItems();

        foreach ($results as $result) {
            $this->subscribers[] = $this->notifierFactory->create();
        }
    }

    public function dispatch()
    {
        foreach ($this->subscribers as $subscriber) {
            $subscriber->notify();
        }
    }
}
