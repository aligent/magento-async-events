<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Api\Data\AsyncEventInterface;
use Aligent\Webhooks\Helper\NotifierResult;

interface NotifierInterface
{
    /**
     * The notifier method
     *
     * @param AsyncEventInterface $asyncEvent
     * @param array $data
     * @return NotifierResult
     */
    public function notify(AsyncEventInterface $asyncEvent, array $data): NotifierResult;
}
