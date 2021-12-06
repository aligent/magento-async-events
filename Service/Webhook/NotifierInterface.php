<?php

namespace Aligent\AsyncEvents\Service\Webhook;

use Aligent\AsyncEvents\Api\Data\AsyncEventInterface;
use Aligent\AsyncEvents\Helper\NotifierResult;

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
