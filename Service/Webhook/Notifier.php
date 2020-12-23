<?php

namespace Aligent\Webhooks\Service\Webhook;

class Notifier
{
    /**
     * @var string
     */
    private string $subscriptionId;

    /**
     * @var string
     */
    private string $objectId;

    public function __construct(string $subscriptionId, string $objectId)
    {
        $this->subscriptionId = $subscriptionId;
        $this->objectId = $objectId;
    }

    public function notify()
    {
        echo "Notifying $this->subscriptionId with $this->objectId \n";
    }
}
