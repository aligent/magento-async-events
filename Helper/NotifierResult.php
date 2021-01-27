<?php


namespace Aligent\Webhooks\Helper;


use Magento\Framework\DataObject;

class NotifierResult extends DataObject
{
    public function getResult() {
        return $this->getData('result');
    }

    public function getMetadata() {
        return $this->getData('metadata');
    }
}
