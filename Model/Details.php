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

class Details
{
    /**
     * @var array
     */
    private $traceCache = [];

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var AsyncEventRepositoryInterface
     */
    private $asyncEventRepository;

    public function __construct(
        AsyncEventLogCollectionFactory $collectionFactory,
        AsyncEventRepositoryInterface  $asyncEventRepository
    ) {
        $this->collection = $collectionFactory->create();
        $this->asyncEventRepository = $asyncEventRepository;
    }

    public function getDetails($uuid)
    {
        if (array_key_exists($uuid, $this->traceCache)) {
            return $this->traceCache[$uuid];
        }

        $this->collection->addFilter('uuid', $uuid);

        $traces = $this->collection->toArray();

        if ($traces['totalRecords'] === 0) {
            return [];
        }

        $asyncEventId = $this->collection->getFirstItem()->getData('subscription_id');
        $asyncEvent = $this->asyncEventRepository->get($asyncEventId)->getData();

        $this->traceCache[$uuid] = [
            'traces' => $traces['items'],
            'async_event' => $asyncEvent
        ];

        return $this->traceCache[$uuid];
    }

    public function getLogs($uuid)
    {
        $this->getDetails($uuid);

        return $this->traceCache[$uuid]['traces'];
    }

    public function getStatus($uuid)
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

    public function getFirstAttempt($uuid)
    {
        $this->getDetails($uuid);

        $traces = $this->traceCache[$uuid]['traces'];
        $firstTrace = current($traces);

        return $firstTrace['created'];
    }

    public function getLastAttempt($uuid)
    {
        $this->getDetails($uuid);

        $traces = $this->traceCache[$uuid]['traces'];
        $firstTrace = end($traces);

        return $firstTrace['created'];
    }

    public function getAsynchronousEventName($uuid)
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['event_name'];
    }

    public function getCurrentStatus($uuid)
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['status'] ? 'Enabled' : 'Disabled';
    }

    public function getRecipient($uuid)
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['recipient_url'];
    }

    public function getSubscribedAt($uuid)
    {
        $this->getDetails($uuid);

        $event = $this->traceCache[$uuid]['async_event'];

        return $event['subscribed_at'];
    }

    public function getPlaceHolder()
    {
        return 'Placeholder';
    }
}
