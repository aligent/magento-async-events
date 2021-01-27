<?php

namespace Aligent\Webhooks\Api\Data;

interface WebhookDisplayInterface
{
    /**
     * @return int|null
     */
    public function getSubscriptionId();

    /**
     * @param int|null $id
     * @return $this
     */
    public function setSubscriptionId($id);

    /**
     * @return string
     */
    public function getEventName();

    /**
     * @param string $eventName
     * @return $this
     */
    public function setEventName($eventName);

    /**
     * @return string
     */
    public function getRecipientUrl();

    /**
     * @param string $recipientURL
     * @return $this
     */
    public function setRecipientUrl($recipientURL);

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getSubscribedAt();

    /**
     * @param string $subscribedAt
     * @return \Aligent\Webhooks\Api\Data\WebhookInterface
     */
    public function setSubscribedAt($subscribedAt);
}
