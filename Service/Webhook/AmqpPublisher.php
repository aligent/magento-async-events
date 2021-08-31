<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Helper\QueueMetadataInterface;
use Magento\Framework\Amqp\Config;
use Magento\Framework\MessageQueue\EnvelopeFactory;
use Magento\Framework\MessageQueue\ExchangeRepository;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class AmqpPublisher implements PublisherInterface
{
    /**
     * @var Config
     */
    private Config $amqpConfig;

    /**
     * @var EnvelopeFactory
     */
    private EnvelopeFactory $envelopeFactory;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @param Config $amqpConfig
     * @param EnvelopeFactory $envelopeFactory
     * @param Json $json
     */
    public function __construct(
        Config $amqpConfig,
        EnvelopeFactory $envelopeFactory,
        Json $json
    ) {
        $this->amqpConfig = $amqpConfig;
        $this->envelopeFactory = $envelopeFactory;
        $this->json = $json;
    }

    public function publish($topicName, $data)
    {
        $data = $this->json->serialize($data);
        $envelope = $this->envelopeFactory->create(
            [
                'body' => $data,
                'properties' => [
                    'delivery_mode' => 2,
                    // md5() here is not for cryptographic use.
                    // phpcs:ignore Magento2.Security.InsecureFunction
                    'message_id' => md5(uniqid($topicName)),
                    'application_headers' => new AMQPTable([
                        'x-retry-count' => 1
                    ])
                ]
            ]
        );

        $channel = $this->amqpConfig->getChannel();
        $msg = new AMQPMessage($envelope->getBody(), $envelope->getProperties());
        $channel->basic_publish($msg, QueueMetadataInterface::FAILOVER_EXCHANGE, $topicName);

        return null;
    }
}
