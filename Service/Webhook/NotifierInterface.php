<?php

namespace Aligent\Webhooks\Service\Webhook;

interface NotifierInterface
{
    /**
     * A custom notifier implementation
     *
     * @return void
     */
    public function notify();
}
