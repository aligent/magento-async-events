<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Model\Config\Data as WebhookConfig;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;

class ModelEventHandler implements ObserverInterface
{
    private ModelToEventName $modelTranslator;

    private EventDispatcher $eventDispatcher;

    private array $events;

    private PublisherInterface $publisher;

    private Json $json;

    public function __construct(
        ModelToEventName $modelTranslator,
        EventDispatcher $eventDispatcher,
        PublisherInterface $publisher,
        Json $json,
        array $events = []
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
            $payload = $this->json->serialize(
                $observer->getData('object')->getId()
            );

            $this->publisher->publish('webhook.trigger', [
                 'webhook_subscriber_save_commit_after', // temp
                 $payload
             ]);
        }
    }
}
