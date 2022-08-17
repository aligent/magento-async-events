<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model;

use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog as AsyncEventLogResource;
use Magento\Framework\Exception\AlreadyExistsException;

class AsyncEventLogRepository
{
    /**
     * @param AsyncEventLogResource $asyncEventLogResource
     */
    public function __construct(
        private readonly AsyncEventLogResource $asyncEventLogResource
    ) {
    }

    /**
     * Save an asynchronous event log
     *
     * @param AsyncEventLog $asyncEvent
     * @return void
     * @throws AlreadyExistsException
     */
    public function save(AsyncEventLog $asyncEvent): void
    {
        $this->asyncEventLogResource->save($asyncEvent);
    }
}
