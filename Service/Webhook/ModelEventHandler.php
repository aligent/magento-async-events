<?php

namespace Aligent\Webhooks\Service\Webhook;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\MessageQueue\PublisherInterface;

class ModelEventHandler implements ObserverInterface
{
    private ModelToEventName $modelTranslator;

    private EventDispatcher $eventDispatcher;

    private array $events;

    private PublisherInterface $publisher;

    public function __construct(
        ModelToEventName $modelTranslator,
        EventDispatcher $eventDispatcher,
        PublisherInterface $publisher,
        array $events
    ) {
        $this->modelTranslator = $modelTranslator;
        $this->eventDispatcher = $eventDispatcher;
        $this->events = $events;
        $this->publisher = $publisher;
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
             $this->publisher->publish('webhook.trigger', [
                 $eventName,
                 $observer->getData('object')->getId()
             ]);

            // TODO: sync mode
//             $this->eventDispatcher->dispatch(
//                 $this->eventDispatcher->loadSubscribers($eventName, $observer->getData('object')->getId())
//             );
        }
    }
}
