<?php

namespace Aligent\Webhooks\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Webhook extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('webhook_subscriber', 'subscription_id');
    }
}
