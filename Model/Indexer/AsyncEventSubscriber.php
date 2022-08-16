<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer;

use Aligent\AsyncEvents\Model\Indexer\DataProvider\AsyncEventSubscriberLogs;
use Aligent\AsyncEvents\Model\Resolver\AsyncEvent;
use ArrayIterator;
use Magento\CatalogSearch\Model\Indexer\IndexerHandlerFactory;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\Indexer\SaveHandler\IndexerInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Psr\Log\LoggerInterface;
use Traversable;

class AsyncEventSubscriber implements
    IndexerActionInterface,
    MviewActionInterface,
    DimensionalIndexerInterface
{
    /**
     * Indexer ID in configuration
     */
    const INDEXER_ID = 'asynchronous_event_subscriber_log';

    /**
     * Default batch size
     */
    const BATCH_SIZE = 100;

    /**
     * Deployment config path
     *
     * @var string
     */
    const DEPLOYMENT_CONFIG_INDEXER_BATCHES = 'indexer/batch_size/';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DimensionProviderInterface
     */
    private $dimensionProvider;

    /**
     * @var IndexerHandlerFactory
     */
    private $indexerHandlerFactory;

    /**
     * @var array index structure
     */
    protected $data;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var DeploymentConfig|null
     */
    private $deploymentConfig;
    /**
     * @var AsyncEventSubscriberLogs
     */
    private $asyncEventSubscriberLogsDataProvider;

    /**
     * @var AsyncEvent
     */
    private $asyncEventScopeResolver;

    /**
     * @param LoggerInterface $logger
     * @param DimensionProviderInterface $dimensionProvider
     * @param IndexerHandlerFactory $indexerHandlerFactory
     * @param AsyncEventSubscriberLogs $asyncEventSubscriberLogsDataProvider
     * @param AsyncEvent $asyncEventScopeResolver
     * @param array $data
     * @param int|null $batchSize
     * @param DeploymentConfig|null $deploymentConfig
     */
    public function __construct(
        LoggerInterface $logger,
        DimensionProviderInterface $dimensionProvider,
        IndexerHandlerFactory $indexerHandlerFactory,
        AsyncEventSubscriberLogs $asyncEventSubscriberLogsDataProvider,
        AsyncEvent $asyncEventScopeResolver,
        array $data,
        int $batchSize = null,
        DeploymentConfig $deploymentConfig = null
    ) {
        $this->logger = $logger;
        $this->dimensionProvider = $dimensionProvider;
        $this->indexerHandlerFactory = $indexerHandlerFactory;
        $this->asyncEventSubscriberLogsDataProvider = $asyncEventSubscriberLogsDataProvider;
        $this->data = $data;
        $this->batchSize = $batchSize ?? self::BATCH_SIZE;
        $this->deploymentConfig = $deploymentConfig ?: ObjectManager::getInstance()->get(DeploymentConfig::class);
        $this->asyncEventScopeResolver = $asyncEventScopeResolver;
    }

    public function executeFull()
    {
        $this->logger->debug(__('Full reindex'));
    }

    public function executeList(array $ids)
    {
        $this->execute($ids);
    }

    public function executeRow($id)
    {
        $this->logger->debug(__('executeRow', [json_encode($id)]));
    }

    public function execute($ids)
    {
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $this->executeByDimensions($dimension, new ArrayIterator($ids));
        }
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds)
    {
        // TODO: Config check for indexing enable
        $saveHandler = $this->indexerHandlerFactory->create(
            [
                'data' => $this->data,
                'scopeResolver' => $this->asyncEventScopeResolver
            ]
        );

        $asyncEventIds = iterator_to_array($entityIds);

        $this->batchSize = $this->deploymentConfig->get(
            self::DEPLOYMENT_CONFIG_INDEXER_BATCHES . self::INDEXER_ID . '/partial_reindex'
        ) ?? $this->batchSize;

        $asyncEventBatches = array_chunk($asyncEventIds, $this->batchSize);

        foreach ($asyncEventBatches as $asyncEventBatch) {
            $this->processBatch(
                $saveHandler,
                $dimensions,
                $asyncEventBatch
            );
        }
    }

    private function processBatch(
        IndexerInterface $saveHandler,
        array $dimensions,
        array $asyncEventLogIds
    ) {
        $this->logger->debug(__('processBatch ' . json_encode($asyncEventLogIds)));
        $asyncEvent = $dimensions[0]->getValue();

        if ($saveHandler->isAvailable($dimensions)) {
            $saveHandler->deleteIndex($dimensions, new ArrayIterator($asyncEventLogIds));
            $saveHandler->saveIndex(
                $dimensions,
                $this->asyncEventSubscriberLogsDataProvider->getAsyncEventLogs(
                    $asyncEventLogIds,
                    $asyncEvent
                )
            );
        }
    }
}
