<?php

namespace Aligent\Webhooks\Service\Webhook;

interface NotifierFactoryInterface
{
    /**
     * @return NotifierInterface|null
     */
    public function create(): ?NotifierInterface;
}
