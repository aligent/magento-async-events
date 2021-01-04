<?php

namespace Aligent\Webhooks\Api\Data;

interface WebhookUpdateInterface
{
    /**
     * @return string|null
     */
    public function getEventName(): ?string;

    /**
     * @param string|null $eventName
     * @return $this
     */
    public function setEventName(?string $eventName): self;

    /**
     * @return string|null
     */
    public function getRecipientUrl(): ?string;

    /**
     * @param string|null $recipientUrl
     * @return $this
     */
    public function setRecipientUrl(?string $recipientUrl): self;

    /**
     * @return string|null
     */
    public function getVerificationToken(): ?string;

    /**
     * @param string|null $verificationToken
     * @return $this
     */
    public function setVerificationToken(?string $verificationToken): self;
}
