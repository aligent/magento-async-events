<?php

namespace Aligent\Webhooks\Service\Webhook;

interface NotifierFactoryInterface
{
    /**
     * Creates a custom notifier implementation instructed by what's stored in the db
     *
     * @return NotifierInterface|null
     */
    public function create(): ?NotifierInterface;
}
