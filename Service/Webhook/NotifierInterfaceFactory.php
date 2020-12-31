<?php

namespace Aligent\Webhooks\Service\Webhook;

interface NotifierInterfaceFactory
{
    /**
     * @return NotifierInterface|null
     */
    public function create(): ?NotifierInterface;
}
