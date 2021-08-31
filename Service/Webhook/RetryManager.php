<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Helper\QueueMetadataInterface;
use Magento\Framework\Amqp\ConfigPool;
use Magento\Framework\Amqp\Topology\BindingInstallerInterface;
use Magento\Framework\Amqp\Topology\QueueInstaller;
use Magento\Framework\MessageQueue\Publisher as AmqpPublisher;
use Magento\Framework\MessageQueue\Topology\Config\ExchangeConfigItem\BindingFactory;
use Magento\Framework\MessageQueue\Topology\Config\QueueConfigItemFactory;

class RetryManager
{
    /**
     * @var ConfigPool
     */
    private ConfigPool $configPool;

    /**
     * @var QueueInstaller
     */
    private QueueInstaller $queueInstaller;

    /**
     * @var BindingInstallerInterface
     */
    private BindingInstallerInterface $bindingInstaller;

    /**
     * @var AmqpPublisher
     */
    private AmqpPublisher $publisher;

    /**
     * @var QueueConfigItemFactory
     */
    private QueueConfigItemFactory $queueConfigItemFactory;

    /**
     * @var BindingFactory
     */
    private BindingFactory $bindingFactory;

    /**
     * @param ConfigPool $configPool
     * @param QueueInstaller $queueInstaller
     * @param BindingInstallerInterface $bindingInstaller
     * @param AmqpPublisher $publisher
     * @param QueueConfigItemFactory $queueConfigItemFactory
     * @param BindingFactory $bindingFactory
     */
    public function __construct(
        ConfigPool $configPool,
        QueueInstaller $queueInstaller,
        BindingInstallerInterface $bindingInstaller,
        AmqpPublisher $publisher,
        QueueConfigItemFactory $queueConfigItemFactory,
        BindingFactory $bindingFactory
    ) {
        $this->queueInstaller = $queueInstaller;
        $this->bindingInstaller = $bindingInstaller;
        $this->configPool = $configPool;
        $this->publisher = $publisher;
        $this->queueConfigItemFactory = $queueConfigItemFactory;
        $this->bindingFactory = $bindingFactory;
    }

    public function place(): void
    {
        $config = $this->configPool->get('amqp');
        $queueConfigItem = $this->queueConfigItemFactory->create();
        $queueConfigItem->setData([
            'name' => QueueMetadataInterface::RETRY_INIT_QUEUE,
            'connection' => 'amqp',
            'durable' => true,
            'autoDelete' => true,
            'arguments' => [
                'x-dead-letter-exchange' => QueueMetadataInterface::FAILOVER_EXCHANGE,
                'x-dead-letter-routing-key' => QueueMetadataInterface::DEAD_LETTER_ROUTING_KEY,
                'x-message-ttl' => 1 * 1000,
                'x-expires' => 1 * 1000 * 2
            ]
        ]);

        $this->queueInstaller->install($config->getChannel(), $queueConfigItem);

        $bindingConfig = $this->bindingFactory->create();
        $bindingConfig->setData([
            'id' => 'WebhookRetryInitializerBinding',
            'destinationType' => 'queue',
            'destination' => QueueMetadataInterface::RETRY_INIT_QUEUE,
            'arguments' => [],
            'topic' => QueueMetadataInterface::RETRY_INIT_ROUTING_KEY,
            'disabled' => false
        ]);

        $this->bindingInstaller->install($config->getChannel(), $bindingConfig, QueueMetadataInterface::FAILOVER_EXCHANGE);

        $this->publisher->publish(QueueMetadataInterface::RETRY_INIT_ROUTING_KEY, ['lorem ipsum dolor']);
    }
}
