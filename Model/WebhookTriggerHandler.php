<?php


namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Model\Config\Data as WebhookConfig;
use Aligent\Webhooks\Service\Webhook\EventDispatcher;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Magento\Framework\Webapi\ServiceOutputProcessor;

class WebhookTriggerHandler
{
    private EventDispatcher $dispatcher;

    private Json $json;

    private ServiceOutputProcessor $outputProcessor;

    private WebhookConfig $webhookConfig;

    private ObjectManagerInterface $objectManager;

    private ServiceInputProcessor $inputProcessor;

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
        // todo: rename variables and refactor
        $eventName = $queueMessage[0];
        $output = $this->json->unserialize($queueMessage[1]);

        $configData = $this->webhookConfig->get($eventName);
        $serviceClassName = $configData['class'];
        $serviceMethodName = $configData['method'];
        $service = $this->objectManager->create($serviceClassName);
        $inputParams = $this->inputProcessor->process($serviceClassName, $serviceMethodName, $output);

        /**
         * @var \Magento\Framework\Api\AbstractExtensibleObject $outputData
         */
        $outputData = call_user_func_array([$service, $serviceMethodName], $inputParams);

        $outputData = $this->outputProcessor->process(
            $outputData,
            $serviceClassName,
            $serviceMethodName
        );

        $this->dispatcher->dispatch($eventName, $outputData);
    }
}
