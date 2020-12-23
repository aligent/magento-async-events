<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\WebhookInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Webhook extends AbstractExtensibleModel implements WebhookInterface
{
    protected $_eventPrefix = 'webhook_subscriber';

    protected function _construct()
    {
        $this->_init(ResourceModel\Webhook::class);
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->getData('event_name');
    }

    /**
     * @param string $eventName
     * @return $this|WebhookInterface
     */
    public function setEventName(string $eventName): WebhookInterface
    {
        $this->setData('event_name', $eventName);
        return $this;
    }

    public function getStatus(): bool
    {
        return $this->getData('status');
    }

    public function setStatus(bool $status): WebhookInterface
    {
        $this->setData('status', $status);
        return $this;
    }

    public function getRecipientUrl(): string
    {
        return $this->getData('recipient_url');
    }

    public function setRecipientUrl(string $recipientURL): WebhookInterface
    {
        $this->setData('recipient_url', $recipientURL);
        return $this;
    }

    public function getVerificationToken(): string
    {
        return $this->getData('verification_token');
    }

    public function setVerificationToken(string $verificationToken): WebhookInterface
    {
        $this->setData('verification_token', $verificationToken);
        return $this;
    }

    public function getSubscribedAt(): \DateTime
    {
        return $this->getData('subscribed_at');
    }

    public function setSubscribedAt(\DateTime $subscribedAt): WebhookInterface
    {
        $this->setData('subscribed_at', $subscribedAt);
        return $this;
    }

    public function getSubscriptionId(): int
    {
        return $this->getData('subscription_id');
    }
}
