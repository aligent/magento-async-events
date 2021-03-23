<?php

namespace Aligent\Webhooks\Model\ResourceModel\Webhook;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'subscription_id';

    protected function _construct()
    {
        $this->_init(
            \Aligent\Webhooks\Model\Webhook::class,
            \Aligent\Webhooks\Model\ResourceModel\Webhook::class
        );
    }
}
