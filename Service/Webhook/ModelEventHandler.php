<?php

namespace Aligent\Webhooks\Service\Webhook;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;

class ModelEventHandler implements ObserverInterface
{
    private ModelToEventName $modelTranslator;

    /**
     * @var EventDispatcher
     */
    private EventDispatcher $eventDispatcher;

    private array $events;

    private PublisherInterface $publisher;
    /**
     * @var Json
     */
    private Json $json;

    public function __construct(
        ModelToEventName $modelTranslator,
        EventDispatcher $eventDispatcher,
        PublisherInterface $publisher,
        Json $json,
        array $events
    ) {
        $this->modelTranslator = $modelTranslator;
        $this->eventDispatcher = $eventDispatcher;
        $this->events = $events;
        $this->publisher = $publisher;
        $this->json = $json;
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
                 $this->json->serialize(
                     $observer->getData('object')->getStoredData()
                 )
             ]);
            // TODO: sync/async mode
            // $this->eventDispatcher->loadSubscribers($eventName);

            // $this->eventDispatcher->dispatch();
        }
    }
}
