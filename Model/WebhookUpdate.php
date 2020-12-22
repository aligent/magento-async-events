<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\WebhookUpdateInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class WebhookUpdate extends AbstractExtensibleModel implements WebhookUpdateInterface
{
    protected $_eventPrefix = 'webhook_update';

    protected function _construct()
    {
        $this->_init(ResourceModel\Webhook::class);
    }

    /**
     * @return string|null
     */
    public function getEventName(): ?string
    {
        return $this->getData('event_name');
    }

    /**
     * @param string $eventName
     * @return WebhookUpdateInterface
     */
    public function setEventName(string $eventName): WebhookUpdateInterface
    {
        $this->setData('event_name', $eventName);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecipientUrl(): ?string
    {
        return $this->getData('recipient_url');
    }

    /**
     * @param string $recipientURL
     * @return WebhookUpdateInterface
     */
    public function setRecipientUrl(string $recipientURL): WebhookUpdateInterface
    {
        $this->setData('recipient_url', $recipientURL);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVerificationToken(): ?string
    {
        return $this->getData('verification_token');
    }

    /**
     * @param string $verificationToken
     * @return WebhookUpdateInterface
     */
    public function setVerificationToken(string $verificationToken): WebhookUpdateInterface
    {
        $this->setData('verification_token', $verificationToken);
        return $this;
    }
}
