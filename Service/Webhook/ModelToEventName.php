<?php

namespace Aligent\Webhooks\Service\Webhook;

use Magento\Framework\Model\AbstractModel;

class ModelToEventName
{
    public function translate(AbstractModel $model)
    {
        return $model->getEventPrefix() . '_updated';
    }
}
