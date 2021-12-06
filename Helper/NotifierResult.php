<?php

namespace Aligent\Webhooks\Helper;

class NotifierResult
{
    private $_success;

    /**
     * Refers back to the actual event entity id that is associated,
     * not the subscription name id like customer_created, customer_updated
     * and so on
     */
    private $_subscriptionId;

    /**
     * Ideally this is a json encoded string
     */
    private $_responseData;

    public function getSuccess()
    {
        return $this->_success;
    }

    public function setSuccess($success)
    {
        $this->_success = $success;
        return $this;
    }

    public function getSubscriptionId()
    {
        return $this->_subscriptionId;
    }

    public function setSubscriptionId($subscriptionId)
    {
        $this->_subscriptionId = $subscriptionId;
        return $this;
    }

    public function getResponseData()
    {
        return $this->_responseData;
    }

    public function setResponseData($responseData)
    {
        $this->_responseData = $responseData;
        return $this;
    }
}
