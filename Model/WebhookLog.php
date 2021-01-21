<?php

namespace Aligent\Webhooks\Model;

use Magento\Framework\Model\AbstractModel;

class WebhookLog extends AbstractModel
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'webhook_subscriber_log';

    protected function _construct()
    {
        $this->_init(ResourceModel\WebhookLog::class);
    }

    public function getLogId()
    {
        $this->getData('log_id');
    }

    public function setLogId($logId)
    {
        $this->setData('log_id', $logId);
        return $this;
    }

    public function getSubscriptionId()
    {
        $this->getData('subscription_id');
    }

    public function setSubscriptionId($subscriptionId)
    {
        $this->setData('subscription_id', $subscriptionId);
        return $this;
    }

    public function getSuccess()
    {
        $this->getData('success');
    }

    public function setSuccess($success)
    {
        $this->setData('success', $success);
        return $this;
    }

    public function getCreated()
    {
        $this->getData('created');
    }

    public function setCreated($created)
    {
        $this->setData('created', $created);
        return $this;
    }
}
