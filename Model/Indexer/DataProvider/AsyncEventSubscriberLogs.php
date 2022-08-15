<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer\DataProvider;

use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog\CollectionFactory as AsyncEventLogCollectionFactory;

class AsyncEventSubscriberLogs
{
    /**
     * @var AsyncEventLogCollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        AsyncEventLogCollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function getAsyncEventLogs(array $logIds, string $asyncEvent)
    {
        $logCollection = $this->collectionFactory->create();
        $logCollection->addFieldToFilter('log_id', ['in' => $logIds]);

        $logCollection->getSelect()
            ->join(
                ['ae' => 'async_event_subscriber'],
                "ae.subscription_id = main_table.subscription_id"
            )
            ->where('ae.event_name = ?', $asyncEvent);

        return $logCollection;
    }
}
