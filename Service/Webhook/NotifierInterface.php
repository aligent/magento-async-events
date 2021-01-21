<?php

namespace Aligent\Webhooks\Service\Webhook;

interface NotifierInterface
{
    /**
     * The notifier method
     *
     * @return bool
     */
    public function notify(): bool;
}
