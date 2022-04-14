<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\ResourceModel\AsyncEvent\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * Override parent method to select manually add all the fields because we do not want to expose the
     * `verification_token` field even if encrypted.
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
