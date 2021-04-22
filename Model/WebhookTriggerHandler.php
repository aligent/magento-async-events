<?php


namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Service\Webhook\EventDispatcher;
use Magento\Framework\Serialize\Serializer\Json;

class WebhookTriggerHandler
{
    private EventDispatcher $dispatcher;

    private Json $json;

    public function __construct(
        EventDispatcher $dispatcher,
        Json $json
    ) {
        $this->dispatcher = $dispatcher;
        $this->json = $json;
    }

    /**
     * @param array $queueMessage
     */
    public function process(array $queueMessage)
    {
        $eventName = $queueMessage[0];
        $output = $this->json->unserialize($queueMessage[1]);

        $this->dispatcher->dispatch($eventName, $output);
    }
}
