<?php

namespace Aligent\Webhooks\Service\Webhook;

use GuzzleHttp\Client;

class HttpNotifier implements NotifierInterface
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
        /// queue
        $this->subscriptionId;
    }
}
