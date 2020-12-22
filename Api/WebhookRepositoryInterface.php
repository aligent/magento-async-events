<?php

namespace Aligent\Webhooks\Api;

interface WebhookRepositoryInterface
{
    /**
     * @param string $subscriptionId
     * @return Data\WebhookInterface
     */
    public function get(string $subscriptionId): Data\WebhookInterface;

    /**
     * @param Data\WebhookInputInterface $webhook
     * @return Data\WebhookInterface
     */
    public function save(Data\WebhookInputInterface $webhook): Data\WebhookInterface;

    /**
     * @param string $subscriptionId
     * @param Data\WebhookUpdateInterface $webhook
     * @return Data\WebhookInterface
     */
    public function update(string $subscriptionId, Data\WebhookUpdateInterface $webhook): Data\WebhookInterface;
}
