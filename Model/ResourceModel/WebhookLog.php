<?php

namespace Aligent\Webhooks\Model\ResourceModel;

class WebhookLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('webhook_subscriber_log', 'log_id');
    }
}
