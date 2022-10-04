<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer;

use Exception;
use Magento\Elasticsearch\SearchAdapter\ConnectionManager;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Filters\FilterModifier;
use Magento\Ui\Component\Filters\Type\Search;

class LuceneSearch extends Search
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param FilterBuilder $filterBuilder
     * @param FilterModifier $filterModifier
     * @param ConnectionManager $connectionManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        FilterBuilder $filterBuilder,
        FilterModifier $filterModifier,
        private readonly ConnectionManager $connectionManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $filterBuilder, $filterModifier, $components, $data);
    }

    /**
     * Prepare the query
     *
     * This function just simply opens a connection to Elasticsearch and delegates the lucene compatible search
     * string to Elasticsearch. If an exception occurs fallback to the default database fulltext search
     * on the log_id column.
     *
     * @return void
     */
    public function prepare(): void
    {
        $client = $this->connectionManager->getConnection();
        $value = $this->getContext()->getRequestParam('search');

        try {
            $rawResponse = $client->query(
                [
                    'index' => 'magento2_async_event_*',
                    'q' => $value
                ]
            );

            $rawDocuments = $rawResponse['hits']['hits'] ?? [];
            $asyncEventIds = array_column($rawDocuments, '_id');

            if (!empty($asyncEventIds)) {
                $filter = $this->filterBuilder->setConditionType('in')
                    ->setField($this->getName())
                    ->setValue($asyncEventIds)
                    ->create();

                $this->getContext()->getDataProvider()->addFilter($filter);
            }
        } catch (Exception) {
            // Fallback to default filter search
            parent::prepare();
        }
    }
}
