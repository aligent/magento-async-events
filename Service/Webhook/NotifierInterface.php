<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Api\Data\WebhookInterface;
use Aligent\Webhooks\Helper\NotifierResult;

interface NotifierInterface
{
    /**
     * The notifier method
     *
     * @param WebhookInterface $webhook
     * @param array $data
     * @return NotifierResult
     */
    public function notify(WebhookInterface $webhook, array $data): NotifierResult;
}
