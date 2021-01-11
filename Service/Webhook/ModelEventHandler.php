<?php

namespace Aligent\Webhooks\Service\Webhook;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ModelEventHandler implements ObserverInterface
{
    private ModelToEventName $modelTranslator;

    /**
     * @var EventDispatcher
     */
    private EventDispatcher $eventDispatcher;

    private array $events;

    public function __construct(
        ModelToEventName $modelTranslator,
        EventDispatcher $eventDispatcher,
        array $events
    ) {
        $this->modelTranslator = $modelTranslator;
        $this->eventDispatcher = $eventDispatcher;
        $this->events = $events;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $eventName = $this->modelTranslator->translate(
            $observer->getData('object')
        );

        if (in_array($eventName, $this->events)) {
            $this->eventDispatcher->loadSubscribers($eventName);

            $this->eventDispatcher->dispatch();
        }
    }
}
