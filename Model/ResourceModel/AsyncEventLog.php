<?php

namespace Aligent\AsyncEvents\Model\ResourceModel;

class AsyncEventLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('async_event_subscriber_log', 'log_id');
    }
}
