<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Aligent\Webhooks\Helper\NotifierResult;
use Aligent\Webhooks\Service\Webhook\NotifierFactoryInterface;
use Aligent\Webhooks\Service\Webhook\RetryManager;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\AlreadyExistsException;

class RetryHandler
{
    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var WebhookRepositoryInterface
     */
    private WebhookRepositoryInterface $webhookRepository;

    /**
     * @var NotifierFactoryInterface
     */
    private NotifierFactoryInterface $notifierFactory;

    /**
     * @var WebhookLogFactory
     */
    private WebhookLogFactory $webhookLogFactory;

    /**
     * @var WebhookLogRepository
     */
    private WebhookLogRepository $webhookLogRepository;

    /**
     * @var RetryManager
     */
    private RetryManager $retryManager;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        WebhookRepositoryInterface $webhookRepository,
        NotifierFactoryInterface $notifierFactory,
        WebhookLogFactory $webhookLogFactory,
        WebhookLogRepository $webhookLogRepository,
        RetryManager $retryManager
    ) {
        $this->webhookRepository = $webhookRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->notifierFactory = $notifierFactory;
        $this->webhookLogFactory = $webhookLogFactory;
        $this->webhookLogRepository = $webhookLogRepository;
        $this->retryManager = $retryManager;
    }

    /**
     * @param array $message
     */
    public function process(array $message)
    {
        [$subscriptionId, $data] = $message;

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', 1)
            ->addFilter('subscription_id', $subscriptionId)
            ->create();

        /** @var Webhook $webhook  */
        [$webhook] = $this->webhookRepository->getList($searchCriteria)->getItems();

        $handler = $webhook->getMetadata();
        $notifier = $this->notifierFactory->create($handler);

        $response = $notifier->notify($webhook, [
            'data' => $data
        ]);

        $this->log($response);

        if (!$response->getSuccess()) {
            if (rand(0, 10) < 5) {
                $this->retryManager->place(2, $subscriptionId, $data);
            } else {
                $this->retryManager->kill($subscriptionId, $data);
            }
        }
    }

    /**
     * @param NotifierResult $response
     */
    private function log(NotifierResult $response): void
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
