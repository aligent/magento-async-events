<?php

namespace Aligent\Webhooks\Api\Data;

interface WebhookDisplayInterface
{
    /**
     * @return int|null
     */
    public function getSubscriptionId();

    /**
     * @return string
     */
    public function getEventName(): string;

    /**
     * @return string
     */
    public function getRecipientUrl(): string;

    /**
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * @return string
     */
    public function getSubscribedAt(): string;
}
