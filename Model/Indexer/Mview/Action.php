<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer\Mview;

use Magento\Framework\Indexer\IndexerInterfaceFactory;
use Magento\Framework\Mview\ActionInterface;

class Action implements ActionInterface
{

    /**
     * @var IndexerInterfaceFactory
     */
    private $indexerFactory;

    public function __construct(IndexerInterfaceFactory $indexerFactory)
    {
        $this->indexerFactory = $indexerFactory;
    }

    public function execute($ids)
    {
        $indexer = $this->indexerFactory->create()->load('async_event_subscriber_log');
        $indexer->reindexList($ids);
    }
}
