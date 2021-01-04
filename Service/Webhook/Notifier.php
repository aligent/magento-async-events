<?php

namespace Aligent\Webhooks\Service\Webhook;

use GuzzleHttp\Client;

class Notifier implements NotifierInterface
{
    /**
     * @var string
     */
    private string $subscriptionId;

    /**
     * @var string
     */
    private string $objectId;
    /**
     * @var Client
     */
    private Client $client;

    public function __construct(string $subscriptionId, string $objectId, Client $client)
    {
        $this->subscriptionId = $subscriptionId;
        $this->objectId = $objectId;
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function notify()
    {
        echo "Notifying subscriber $this->subscriptionId with $this->objectId" . PHP_EOL;
        // $this->client->get('https://webhook.site/5106230a-a8ee-45f8-9887-fc75890d28b9?id=1&sd=1');
    }
}
