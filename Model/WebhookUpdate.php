<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\WebhookUpdateInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class WebhookUpdate extends AbstractExtensibleModel implements WebhookUpdateInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'webhook_update';

    protected function _construct()
    {
        $this->_init(ResourceModel\Webhook::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getEventName(): ?string
    {
        return $this->getData('event_name');
    }

    /**
     * {@inheritDoc}
     */
    public function setEventName(?string $eventName): WebhookUpdateInterface
    {
        $this->setData('event_name', $eventName);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRecipientUrl(): ?string
    {
        return $this->getData('recipient_url');
    }

    /**
     * {@inheritDoc}
     */
    public function setRecipientUrl(?string $recipientURL): WebhookUpdateInterface
    {
        $this->setData('recipient_url', $recipientURL);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getVerificationToken(): ?string
    {
        return $this->getData('verification_token');
    }

    /**
     * {@inheritDoc}
     */
    public function setVerificationToken(?string $verificationToken): WebhookUpdateInterface
    {
        $this->setData('verification_token', $verificationToken);
        return $this;
    }
}
