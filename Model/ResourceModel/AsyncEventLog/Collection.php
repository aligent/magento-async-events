<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog;

use Aligent\AsyncEvents\Model\AsyncEventLog;
use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog as AsyncEventLogResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'log_id';

    protected function _construct()
    {
        $this->_init(
            AsyncEventLog::class,
            AsyncEventLogResource::class
        );
    }
}
