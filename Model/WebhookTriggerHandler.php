<?php


namespace Aligent\Webhooks\Model;


use Psr\Log\LoggerInterface;

class WebhookTriggerHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(array $messages)
    {
        $this->logger->critical('Consuming from queue: ', $messages);
    }
}
