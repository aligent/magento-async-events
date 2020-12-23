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
     * @var string
     */
    private string $eventName;

    public function __construct(WebhookRepositoryInterface $webhookRepository, SearchCriteriaBuilder $searchCriteriaBuilder, $eventName)
    {
        $this->webhookRepository = $webhookRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->eventName = $eventName;

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', 1)
            ->addFilter('event_name', $this->eventName)
            ->create();

        $results = $this->webhookRepository->getList($searchCriteria)->getItems();

        foreach ($results as $result) {
            $this->subscribers[] = new Notifier($result["subscription_id"], rand(0, 10));
        }
    }

    public function dispatch()
    {
        foreach ($this->subscribers as $subscriber) {
            $subscriber->notify();
        }
    }
}
