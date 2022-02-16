<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AsyncEventLog extends AbstractDb
{

    protected $_serializableFields = ['serialized_data' => [[],[]]];

    protected function _construct()
    {
        $this->_init('async_event_subscriber_log', 'log_id');
    }
}
