<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer;

use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Psr\Log\LoggerInterface;
use Traversable;

class AsyncEventSubscriber implements
    IndexerActionInterface,
    MviewActionInterface,
    DimensionalIndexerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function executeFull()
    {
        $this->logger->debug(__('Full reindex'));
    }

    public function executeList(array $ids)
    {
        $this->logger->debug(__(json_encode($ids)));
    }

    public function executeRow($id)
    {
        $this->logger->debug(__(json_encode($id)));
    }

    public function execute($ids)
    {
        $this->logger->debug(__(json_encode($ids)));
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds)
    {
        $this->logger->debug(__(json_encode($dimensions)));
    }
}
