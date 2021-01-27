<?php


namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Helper\NotifierResult;

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
    public function notify(): NotifierResult
    {
        // Do something here with any data
        $data = "Example notifier with some data: $this->exampleData  \n";

        return new NotifierResult([
            'result' => true,
            'metadata' => $this->exampleData
        ]);
    }
}
