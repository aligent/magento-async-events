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
    public function getSubscriptionId()
    {
        return $this->getData('subscription_id');
    }

    /**
     * {@inheritDoc}
     */
    public function getEventName()
    {
        return $this->getData('event_name');
    }

    /**
     * {@inheritDoc}
     */
    public function setEventName($eventName)
    {
        $this->setData('event_name', $eventName);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRecipientUrl()
    {
        return $this->getData('recipient_url');
    }

    /**
     * {@inheritDoc}
     */
    public function setRecipientUrl($recipientURL)
    {
        $this->setData('recipient_url', $recipientURL);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getVerificationToken()
    {
        return $this->getData('verification_token');
    }

    /**
     * {@inheritDoc}
     */
    public function setVerificationToken($verificationToken)
    {
        $this->setData('verification_token', $verificationToken);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {
        return $this->getData('status');
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {
        $this->setData('status', $status);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedAt()
    {
        return $this->getData('subscribed_at');
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscribedAt($subscribedAt)
    {
        $this->setData('subscribed_at', $subscribedAt);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscriptionId($id)
    {
        return $this->setData('subscription_id', $id);
    }

    /**
     * @return string
     */
    public function getMetadata()
    {
        return $this->getData('metadata');
    }

    /**
     * @param string $metadata
     * @return $this
     */
    public function setMetadata($metadata)
    {
        $this->setData('metadata', $metadata);
        return $this;
    }
}
