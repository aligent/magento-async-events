<?php

namespace Aligent\Webhooks\Api\Data;

interface WebhookInterface
{
    /**
     * @return int
     */
    public function getSubscriptionId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setSubscriptionId(int $id): void;

    /**
     * @return string
     */
    public function getEventName(): string;

    /**
     * @param string $eventName
     * @return void
     */
    public function setEventName(string $eventName): void;

    /**
     * @return string
     */
    public function getRecipientUrl(): string;

    /**
     * @param string $recipientURL
     * @return void
     */
    public function setRecipientUrl(string $recipientURL): void;

    /**
     * @return string
     */
    public function getVerificationToken(): string;

    /**
     * @param string $verificationToken
     * @return void
     */
    public function setVerificationToken(string $verificationToken): void;

    /**
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * @param bool $status
     * @return void
     */
    public function setStatus(bool $status): void;

    /**
     * @return string
     */
    public function getSubscribedAt(): string;

    /**
     * @param string $subscribedAt
     * @return void
     */
    public function setSubscribedAt(string $subscribedAt): void;

    /**
     * @return string
     */
    public function getMetadata(): string;

    /**
     * @param string $metadata
     * @return void
     */
    public function setMetadata(string $metadata): void;
}
