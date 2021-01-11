<?php

namespace Aligent\Webhooks\Service\Webhook;

interface NotifierInterface
{
    /**
     * The notifier method
     */
    public function notify();
}
