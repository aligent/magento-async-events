<?php

namespace Aligent\Webhooks\Api\Data;

interface WebhookInterface
{
    /**
     * @return int
     */
    public function getSubscriptionId(): int;

    /**
     * @return string
     */
    public function getEventName(): string;

    /**
     * @param string $eventName
     * @return $this
     */
    public function setEventName(string $eventName): self;

    /**
     * @return string
     */
    public function getRecipientUrl(): string;

    /**
     * @param string $recipientURL
     * @return $this
     */
    public function setRecipientUrl(string $recipientURL): self;

    /**
     * @return string
     */
    public function getVerificationToken(): string;

    /**
     * @param string $verificationToken
     * @return WebhookInterface
     */
    public function setVerificationToken(string $verificationToken): self;

    /**
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status): self;

    /**
     * @return \DateTime
     */
    public function getSubscribedAt(): \DateTime;

    /**
     * @param \DateTime $subscribedAt
     * @return WebhookInterface
     */
    public function setSubscribedAt(\DateTime $subscribedAt): WebhookInterface;
}
