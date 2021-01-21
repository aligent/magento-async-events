<?php


namespace Aligent\Webhooks\Service\Webhook;

class ExampleNotifier implements NotifierInterface
{
    private ?string $exampleData;

    public function __construct($subscription_id)
    {
        $this->exampleData = $subscription_id;
    }

    /**
     * {@inheritDoc}
     */
    public function notify(): bool
    {
        // Do something here with any data
        $data = "Example notifier with some data: $this->exampleData  \n";

        return true;
    }
}
