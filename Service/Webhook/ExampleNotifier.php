<?php

namespace Aligent\AsyncEvents\Service\Webhook;

use Aligent\AsyncEvents\Api\Data\AsyncEventInterface;
use Aligent\AsyncEvents\Helper\NotifierResult;

class ExampleNotifier implements NotifierInterface
{
    /**
     * {@inheritDoc}
     */
    public function notify(AsyncEventInterface $asyncEvent, array $data): NotifierResult
    {
        // Do something here with any data
        $data = "Example notifier with some data: " . $data["objectId"];

        $result = new NotifierResult();
        $result->setSuccess(true);
        $result->setSubscriptionId($asyncEvent->getSubscriptionId());
        $result->setResponseData($data);

        return $result;
    }
}
