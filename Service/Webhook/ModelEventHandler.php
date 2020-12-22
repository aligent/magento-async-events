<?php

namespace Aligent\Webhooks\Service\Webhook;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ModelEventHandler implements ObserverInterface
{
    private ModelToEventName $modelTranslator;

    private EventDispatcher $dispatcher;

    public function __construct(ModelToEventName $modelTranslator, EventDispatcher $dispatcher)
    {
        $this->modelTranslator = $modelTranslator;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        // load all subscribers to something.updated
    }
}
