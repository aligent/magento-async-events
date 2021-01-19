<?php

namespace Aligent\Webhooks\Api\Data;

interface WebhookInterface
{
    /**
     * @return int|null
     */
    public function getSubscriptionId(): ?int;

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
     * @return string
     */
    public function getSubscribedAt(): string;

    /**
     * @param string $subscribedAt
     * @return WebhookInterface
     */
    public function setSubscribedAt(string $subscribedAt): WebhookInterface;
}
