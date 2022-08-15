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

    public function getAsyncEventLogs(array $logIds)
    {
        $logCollection = $this->collectionFactory->create();
        $logCollection->addFieldToFilter('log_id', ['in' => $logIds]);

        return $logCollection;
    }
}
