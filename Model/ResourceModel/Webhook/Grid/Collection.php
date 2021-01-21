<?php

namespace Aligent\Webhooks\Model\ResourceModel\Webhook\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable,
        $resourceModel = null,
        $identifierName = null,
        $connectionName = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel,
            $identifierName,
            $connectionName
        );
    }

    /**
     * Override parent method to select specific fields
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->addFieldToSelect([
            'subscription_id',
            'event_name',
            'recipient_url',
            'status',
            'subscribed_at',
            'modified'
        ]);

        return $this;
    }
}
