<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model;

use Aligent\AsyncEvents\Api\AsyncEventRepositoryInterface;
use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog\Collection;
use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog\CollectionFactory as AsyncEventLogCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Details
{
    /**
     * @var array
     */
    private $traceCache = [];

    /**
     * @var AsyncEventRepositoryInterface
     */
    private $asyncEventRepository;

    /**
     * @var AsyncEventLogCollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        AsyncEventLogCollectionFactory $collectionFactory,
        AsyncEventRepositoryInterface  $asyncEventRepository
    ) {
        $this->asyncEventRepository = $asyncEventRepository;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param string $uuid
     * @return array
     * @throws NoSuchEntityException
     */
    public function getDetails(string $uuid): array
    {
        if (array_key_exists($uuid, $this->traceCache)) {
            return $this->traceCache[$uuid];
        }

        $collection = $this->collectionFactory->create();
        $collection->addFilter('uuid', $uuid);

        $traces = $collection->toArray();

        if ($traces['totalRecords'] === 0) {
            return [];
        }

        $asyncEventId = $collection->getFirstItem()->getData('subscription_id');
        $asyncEvent = $this->asyncEventRepository->get($asyncEventId)->getData();

        $this->traceCache[$uuid] = [
            'traces' => $traces['items'],
            'async_event' => $asyncEvent
        ];

        return $this->traceCache[$uuid];
    }

    /**
     * @param string $uuid
     * @return array
     * @throws NoSuchEntityException
     */
    public function getLogs(string $uuid): array
    {
        $this->getDetails($uuid);

        return $this->traceCache[$uuid]['traces'];
    }

    /**
     * @param string $uuid
     * @return string
     * @throws NoSuchEntityException
     */
    public function getStatus(string $uuid): string
    {
        $this->getDetails($uuid);

        $traces = $this->traceCache[$uuid]['traces'];

        $deathCount = 0;
        $success = false;
        foreach ($traces as $trace) {
            $deathCount++;

            if ($trace['success']) {
                $success = true;
                break;
            }
        }

        // Deduct one which is taking the initial delivery into consideration
        $deathCount--;

        if ($success) {
            $message = 'Delivered';
        } else {
            $message = 'Dead Lettered';
        }

        if ($deathCount > 0) {
            $message = $message . ' with ' . $deathCount . ' retries';
        }

        return $message;
    }

    /**
     * @param string $uuid
     * @return string
     * @throws NoSuchEntityException
     */
    public function getFirstAttempt(string $uuid): string
    {
        $this->getDetails($uuid);

        $traces = $this->traceCache[$uuid]['traces'];
        $firstTrace = current($traces);

        return $firstTrace['created'];
    }

    /**
     * @param string $uuid
     * @return string
     * @throws NoSuchEntityException
     */
    public function getLastAttempt(string $uuid): string
    {
        $this->getDetails($uuid);

        $traces = $this->traceCache[$uuid]['traces'];
        $firstTrace = end($traces);

        return $firstTrace['created'];
    }

    /**
     * @param string $uuid
     * @return string
     * @throws NoSuchEntityException
     */
    public function getAsynchronousEventName(string $uuid): string
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['event_name'];
    }

    /**
     * @param string $uuid
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCurrentStatus(string $uuid): string
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['status'] ? 'Enabled' : 'Disabled';
    }

    /**
     * @param string $uuid
     * @return string
     * @throws NoSuchEntityException
     */
    public function getRecipient(string $uuid): string
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['recipient_url'];
    }

    /**
     * @param string $uuid
     * @return string
     * @throws NoSuchEntityException
     */
    public function getSubscribedAt(string $uuid): string
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['subscribed_at'];
    }
}
