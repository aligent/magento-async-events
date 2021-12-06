<?php

namespace Aligent\Webhooks\Model\ResourceModel\Webhook;

use Aligent\Webhooks\Model\ResourceModel\AsyncEvent as AsyncEventResource;
use Aligent\Webhooks\Model\AsyncEvent;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'subscription_id';

    protected function _construct()
    {
        $this->_init(
            AsyncEvent::class,
            AsyncEventResource::class
        );
    }
}
