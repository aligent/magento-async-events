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
use Magento\Framework\Serialize\SerializerInterface;

class RetryHandler
{
    const RETRY_LIMIT = 5;

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

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param WebhookRepositoryInterface $webhookRepository
     * @param NotifierFactoryInterface $notifierFactory
     * @param WebhookLogFactory $webhookLogFactory
     * @param WebhookLogRepository $webhookLogRepository
     * @param RetryManager $retryManager
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        WebhookRepositoryInterface $webhookRepository,
        NotifierFactoryInterface $notifierFactory,
        WebhookLogFactory $webhookLogFactory,
        WebhookLogRepository $webhookLogRepository,
        RetryManager $retryManager,
        SerializerInterface $serializer
    ) {
        $this->webhookRepository = $webhookRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->notifierFactory = $notifierFactory;
        $this->webhookLogFactory = $webhookLogFactory;
        $this->webhookLogRepository = $webhookLogRepository;
        $this->retryManager = $retryManager;
        $this->serializer = $serializer;
    }

    /**
     * @param array $message
     */
    public function process(array $message)
    {
        $subscriptionId = $message[RetryManager::SUBSCRIPTION_ID];
        $deathCount = $message[RetryManager::DEATH_COUNT];
        $data = $message[RetryManager::CONTENT];

        $subscriptionId = (int) $subscriptionId;
        $deathCount = (int) $deathCount;

        $data = $this->serializer->unserialize($data);

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', 1)
            ->addFilter('subscription_id', $subscriptionId)
            ->create();

        $webhooks = $this->webhookRepository->getList($searchCriteria)->getItems();

        foreach ($webhooks as $webhook) {
            $handler = $webhook->getMetadata();
            $notifier = $this->notifierFactory->create($handler);
            $response = $notifier->notify($webhook, [
                'data' => $data
            ]);

            $this->log($response);

            if (!$response->getSuccess()) {
                if ($deathCount < self::RETRY_LIMIT) {
                    $this->retryManager->place($deathCount + 1, $subscriptionId, $data);
                } else {
                    $this->retryManager->kill($subscriptionId, $data);
                }
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
