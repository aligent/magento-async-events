<?php

namespace Aligent\Webhooks\Service\Webhook;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ModelEventHandler implements ObserverInterface
{
    private ModelToEventName $modelTranslator;

    /**
     * @var EventDispatcherFactory
     */
    private EventDispatcherFactory $eventDispatcherFactory;

    public function __construct(ModelToEventName $modelTranslator, EventDispatcherFactory $eventDispatcherFactory)
    {
        $this->modelTranslator = $modelTranslator;
        $this->eventDispatcherFactory = $eventDispatcherFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $eventName = $this->modelTranslator->translate(
            $observer->getData('object')
        );

        $dispatcher = $this->eventDispatcherFactory->create(['eventName' => $eventName]);

        $dispatcher->dispatch();
    }
}
