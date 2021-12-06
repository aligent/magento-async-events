<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Model\ResourceModel\AsyncEventLog as WebhookResource;
use Magento\Framework\Exception\AlreadyExistsException;

class WebhookLogRepository
{
    /**
     * @var WebhookResource
     */
    private $webhookLogResource;

    public function __construct(
        WebhookResource $webhookLogResource
    ) {
        $this->webhookLogResource = $webhookLogResource;
    }

    /**
     * @param WebhookLog $webhookLog
     * @throws AlreadyExistsException
     */
    public function save($webhookLog)
    {
        $this->webhookLogResource->save($webhookLog);
    }
}
