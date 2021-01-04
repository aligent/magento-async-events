<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\WebhookInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Webhook extends AbstractExtensibleModel implements WebhookInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'webhook_subscriber';

    protected function _construct()
    {
        $this->_init(ResourceModel\Webhook::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionId(): int
    {
        return $this->getData('subscription_id');
    }

    /**
     * {@inheritDoc}
     */
    public function getEventName(): string
    {
        return $this->getData('event_name');
    }

    /**
     * {@inheritDoc}
     */
    public function setEventName(string $eventName): WebhookInterface
    {
        $this->setData('event_name', $eventName);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRecipientUrl(): string
    {
        return $this->getData('recipient_url');
    }

    /**
     * {@inheritDoc}
     */
    public function setRecipientUrl(string $recipientURL): WebhookInterface
    {
        $this->setData('recipient_url', $recipientURL);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getVerificationToken(): string
    {
        return $this->getData('verification_token');
    }

    /**
     * {@inheritDoc}
     */
    public function setVerificationToken(string $verificationToken): WebhookInterface
    {
        $this->setData('verification_token', $verificationToken);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): bool
    {
        return $this->getData('status');
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(bool $status): WebhookInterface
    {
        $this->setData('status', $status);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedAt(): \DateTime
    {
        return $this->getData('subscribed_at');
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscribedAt(\DateTime $subscribedAt): WebhookInterface
    {
        $this->setData('subscribed_at', $subscribedAt);
        return $this;
    }
}
