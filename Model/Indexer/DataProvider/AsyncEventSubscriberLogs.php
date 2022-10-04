<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer\DataProvider;

use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog\Collection;
use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog\CollectionFactory as AsyncEventLogCollectionFactory;

class AsyncEventSubscriberLogs
{
    /**
     * @param AsyncEventLogCollectionFactory $collectionFactory
     */
    public function __construct(
        private readonly AsyncEventLogCollectionFactory $collectionFactory
    ) {
    }

    /**
     * Get async event logs by log ids
     *
     * @param array $logIds
     * @param string $asyncEvent
     * @return Collection
     */
    public function getAsyncEventLogs(array $logIds, string $asyncEvent)
    {
        $logCollection = $this->collectionFactory->create();
        $logCollection->addFieldToFilter('log_id', ['in' => $logIds]);

        $logCollection->getSelect()
            ->join(
                ['ae' => 'async_event_subscriber'],
                'ae.subscription_id = main_table.subscription_id',
                ['event_name']
            )
            ->where('ae.event_name = ?', $asyncEvent);

        return $logCollection;
    }
}
