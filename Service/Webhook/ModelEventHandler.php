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

    public function __construct(
        ModelToEventName $modelTranslator,
        EventDispatcher $eventDispatcher
    ) {
        $this->modelTranslator = $modelTranslator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $eventName = $this->modelTranslator->translate(
            $observer->getData('object')
        );

        $this->eventDispatcher->loadSubscribers($eventName);

        $this->eventDispatcher->dispatch();
    }
}
