<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Helper\NotifierResult;

interface NotifierInterface
{
    /**
     * The notifier method
     *
     * @return bool
     */
    public function notify(): NotifierResult;
}
