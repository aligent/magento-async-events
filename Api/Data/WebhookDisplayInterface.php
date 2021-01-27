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
    public function getEventName();

    /**
     * @return string
     */
    public function getRecipientUrl();

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getSubscribedAt();
}
