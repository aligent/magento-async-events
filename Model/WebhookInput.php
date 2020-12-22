<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\WebhookInputInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class WebhookInput extends AbstractExtensibleModel implements WebhookInputInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'webhook_input';

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
     * @return WebhookInputInterface
     */
    public function setEventName(string $eventName): WebhookInputInterface
    {
        $this->setData('event_name', $eventName);
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientUrl(): string
    {
        return $this->getData('recipient_url');
    }

    /**
     * @param string $recipientURL
     * @return WebhookInputInterface
     */
    public function setRecipientUrl(string $recipientURL): WebhookInputInterface
    {
        $this->setData('recipient_url', $recipientURL);
        return $this;
    }

    /**
     * @return string
     */
    public function getVerificationToken(): string
    {
        return $this->getData('verification_token');
    }

    /**
     * @param string $verificationToken
     * @return WebhookInputInterface
     */
    public function setVerificationToken(string $verificationToken): WebhookInputInterface
    {
        $this->setData('verification_token', $verificationToken);
        return $this;
    }
}
