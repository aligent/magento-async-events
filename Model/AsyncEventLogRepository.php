<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Model\ResourceModel\AsyncEventLog as AsyncEventLogResource;
use Magento\Framework\Exception\AlreadyExistsException;

class AsyncEventLogRepository
{
    /**
     * @var AsyncEventLogResource
     */
    private $asyncEventLogResource;

    public function __construct(
        AsyncEventLogResource $asyncEventLog
    ) {
        $this->asyncEventLogResource = $asyncEventLog;
    }

    /**
     * @param AsyncEventLog $asyncEvent
     * @throws AlreadyExistsException
     */
    public function save(AsyncEventLog $asyncEvent)
    {
        $this->asyncEventLogResource->save($asyncEvent);
    }
}
