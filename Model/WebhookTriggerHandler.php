<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Model\Config as WebhookConfig;
use Aligent\Webhooks\Service\Webhook\EventDispatcher;
use Exception;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Magento\Framework\Webapi\ServiceOutputProcessor;
use Psr\Log\LoggerInterface;

class WebhookTriggerHandler
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var ServiceOutputProcessor
     */
    private $outputProcessor;

    /**
     * @var Config
     */
    private $webhookConfig;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var ServiceInputProcessor
     */
    private $inputProcessor;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param EventDispatcher $dispatcher
     * @param ServiceOutputProcessor $outputProcessor
     * @param ObjectManagerInterface $objectManager
     * @param Config $webhookConfig
     * @param ServiceInputProcessor $inputProcessor
     * @param Json $json
     * @param LoggerInterface $logger
     */
    public function __construct(
        EventDispatcher $dispatcher,
        ServiceOutputProcessor $outputProcessor,
        ObjectManagerInterface $objectManager,
        WebhookConfig $webhookConfig,
        ServiceInputProcessor $inputProcessor,
        Json $json,
        LoggerInterface $logger
    ) {
        $this->dispatcher = $dispatcher;
        $this->json = $json;
        $this->outputProcessor = $outputProcessor;
        $this->webhookConfig = $webhookConfig;
        $this->objectManager = $objectManager;
        $this->inputProcessor = $inputProcessor;
        $this->logger = $logger;
    }

    /**
     * @param array $queueMessage
     */
    public function process(array $queueMessage)
    {
        try {
            // In every publish the data is an array of strings, the first string is the hook name itself, the second
            // name is a serialised string of parameters that the service method accepts.
            // In a future major version this will change to a schema type e.g: WebhookMessageInterface
            $eventName = $queueMessage[0];
            $output = $this->json->unserialize($queueMessage[1]);

            $configData = $this->webhookConfig->get($eventName);
            $serviceClassName = $configData['class'];
            $serviceMethodName = $configData['method'];
            $service = $this->objectManager->create($serviceClassName);
            $inputParams = $this->inputProcessor->process($serviceClassName, $serviceMethodName, $output);

            $outputData = call_user_func_array([$service, $serviceMethodName], $inputParams);

            $outputData = $this->outputProcessor->process(
                $outputData,
                $serviceClassName,
                $serviceMethodName
            );

            $this->dispatcher->dispatch($eventName, $outputData);
        } catch (Exception $exception) {
            $this->logger->critical(
                __('Error when processing %hook webhook', [
                    'hook' => $eventName
                ]),
                [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );
        }
    }
}
