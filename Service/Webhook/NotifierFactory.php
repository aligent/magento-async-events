<?php

namespace Aligent\Webhooks\Service\Webhook;

use GuzzleHttp\Client;

class NotifierFactory implements NotifierFactoryInterface
{
    /**
     * @var Client
     */
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     * @return NotifierInterface|null
     */
    public function create(): ?NotifierInterface
    {
        if (true) {
            return new Notifier(1, 1, $this->client);
        }

        // TODO: Throw exception
        return null;
    }
}
