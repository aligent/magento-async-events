<?php

namespace Aligent\Webhooks\Helper;

interface QueueMetadataInterface
{
    /**
     * Defines the queue topic name in one place so it's easier to reference across in observers and publishers
     */
    const WEBHOOK_QUEUE = 'webhook.trigger';

    const FAILOVER_EXCHANGE = 'webhook.failover';

    const RETRY_INIT_QUEUE = 'webhook.delay.1';

    const RETRY_INIT_ROUTING_KEY = 'webhook.retry.init';

    const DEAD_LETTER_ROUTING_KEY = 'webhook.retry';
}
