<?php

namespace Aligent\Webhooks\Model\ResourceModel;

class AsyncEventLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('webhook_subscriber_log', 'log_id');
    }
}
