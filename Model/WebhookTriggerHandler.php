<?php


namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Service\Webhook\EventDispatcher;
use Psr\Log\LoggerInterface;

class WebhookTriggerHandler
{
    /**
     * @var EventDispatcher
     */
    private EventDispatcher $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param array $messages
     */
    public function process(array $messages)
    {
        $this->dispatcher->dispatch(
            $this->dispatcher->loadSubscribers($messages[0], $messages[1])
        );
    }
}
