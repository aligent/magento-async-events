<?php


namespace Aligent\Webhooks\Service\Webhook;

class ExampleNotifier implements NotifierInterface
{
    private ?string $exampleData;

    public function __construct($subscription_id)
    {
        $this->exampleData = $subscription_id;
    }

    public function notify()
    {
        return "Example notifier with some data: $this->exampleData  \n";
    }
}
