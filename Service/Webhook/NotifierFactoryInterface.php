<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Model\Webhook;

interface NotifierFactoryInterface
{
    /**
     * Creates a custom notifier implementation instructed by what's stored in the db
     *
     * @param Webhook $data
     * @param string $objectData
     * @return NotifierInterface
     */
    public function create(Webhook $data, string $objectData): NotifierInterface;
}
