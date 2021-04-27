<?php

namespace Aligent\Webhooks\Helper;

interface QueueMetadataInterface
{
    /**
     * Defines the queue topic name in one place so it's easier to reference across in observers and publishers
     */
    const WEBHOOK_QUEUE = 'webhook.trigger';
}
