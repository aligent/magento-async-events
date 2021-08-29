<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Model\Config as WebhookConfig;
use Aligent\Webhooks\Service\Webhook\EventDispatcher;
use Exception;
use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Magento\Framework\Webapi\ServiceOutputProcessor;

class WebhookTriggerHandler
{
    /**
     * @var EventDispatcher
     */
    private EventDispatcher $dispatcher;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @var ServiceOutputProcessor
     */
    private ServiceOutputProcessor $outputProcessor;

    /**
     * @var Config
     */
    private WebhookConfig $webhookConfig;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @var ServiceInputProcessor
     */
    private ServiceInputProcessor $inputProcessor;

    /**
     * @param EventDispatcher $dispatcher
     * @param ServiceOutputProcessor $outputProcessor
     * @param ObjectManagerInterface $objectManager
     * @param Config $webhookConfig
     * @param ServiceInputProcessor $inputProcessor
     * @param Json $json
     */
    public function __construct(
        EventDispatcher $dispatcher,
        ServiceOutputProcessor $outputProcessor,
        ObjectManagerInterface $objectManager,
        WebhookConfig $webhookConfig,
        ServiceInputProcessor $inputProcessor,
        Json $json
    ) {
        $this->dispatcher = $dispatcher;
        $this->json = $json;
        $this->outputProcessor = $outputProcessor;
        $this->webhookConfig = $webhookConfig;
        $this->objectManager = $objectManager;
        $this->inputProcessor = $inputProcessor;
    }

    /**
     * @param array $queueMessage
     */
    public function process(array $queueMessage)
    {
        try {
            $eventName = $queueMessage[0];
            $output = $this->json->unserialize($queueMessage[1]);

            $configData = $this->webhookConfig->get($eventName);
            $serviceClassName = $configData['class'];
            $serviceMethodName = $configData['method'];
            $service = $this->objectManager->create($serviceClassName);
            $inputParams = $this->inputProcessor->process($serviceClassName, $serviceMethodName, $output);

            /**
             * @var AbstractExtensibleObject $outputData
             */
            $outputData = call_user_func_array([$service, $serviceMethodName], $inputParams);

            $outputData = $this->outputProcessor->process(
                $outputData,
                $serviceClassName,
                $serviceMethodName
            );

            $this->dispatcher->dispatch($eventName, $outputData);
        } catch (Exception $exception) {
            // TODO
        }
    }
}
