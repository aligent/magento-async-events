<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\ResourceModel\AsyncEvent\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Collection extends SearchResult
{
    /**
     * Override parent method to select specific fields
     *
     * @return $this
     */
    protected function _initSelect(): Collection
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
