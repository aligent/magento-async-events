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
     */
    public function create(array $webhook, string $objectData): NotifierInterface
    {
        // TODO: subscription_id as switch case is just a placeholder for now, actual implementation must use a relevant
        // field
        switch ($webhook['subscription_id']) {
            case 'HTTP':
                return new HttpNotifier($webhook['subscription_id'], $objectData, $this->client);
            default:
                // TODO: use a default fallback notifier or throw an exception
                return new ExampleNotifier($objectData);
        }
    }
}
