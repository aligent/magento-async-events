<?php

namespace Aligent\Webhooks\Service\Webhook;

use Magento\Framework\Model\AbstractModel;

class ModelToEventName
{
    public function translate(AbstractModel $model)
    {
        if ($model->isObjectNew()) {
            return $model->getEventPrefix() . '_created';
        }

        return $model->getEventPrefix() . '_updated';
    }
}
