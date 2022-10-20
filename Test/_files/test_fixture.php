<?php

use Aligent\AsyncEvents\Api\Data\AsyncEventInterface;
use Aligent\AsyncEvents\Api\Data\AsyncEventInterfaceFactory;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

$categoryFactory = $objectManager->get(AsyncEventInterfaceFactory::class);

/** @var AsyncEventInterface $category */
$category = $categoryFactory->create(
    [
        'data' => [
            'subscription_id' => 1,
            'event' => 'example.event',
            'recipient_url' => 'http://host.docker.internal:3001/failable',
            'verification_token' => 'supersecret',
            'status' => 1
        ]
    ]
);

$category->save();
