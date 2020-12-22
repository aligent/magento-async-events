<?php

namespace Aligent\Webhooks\Api\Data;

interface WebhookInputInterface
{
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
     * @param string $recipientUrl
     * @return $this
     */
    public function setRecipientUrl(string $recipientUrl): self;

    /**
     * @return string
     */
    public function getVerificationToken(): string;

    /**
     * @param string $verificationToken
     * @return $this
     */
    public function setVerificationToken(string $verificationToken): self;
}
