<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Block\Adminhtml\Trace\Tab\View;

use Aligent\AsyncEvents\Model\Details;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class Info extends Template
{
    /**
     * @var Details
     */
    private $details;

    private $uuid;

    public function __construct(
        Context $context,
        Details $details,
        array $data = []
    ) {
        $this->details = $details;
        parent::__construct($context, $data);
        $this->uuid = $this->getRequest()->getParam('uuid');
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getLogs()
    {
        return $this->details->getLogs($this->uuid);
    }

    public function getStatus()
    {
        return $this->details->getStatus($this->uuid);
    }

    public function getFirstAttempt()
    {
        return $this->details->getFirstAttempt($this->uuid);
    }

    public function getLastAttempt()
    {
        return $this->details->getLastAttempt($this->uuid);
    }

    public function getAsynchronousEventName()
    {
        return $this->details->getAsynchronousEventName($this->uuid);
    }

    public function getCurrentStatus()
    {
        return $this->details->getCurrentStatus($this->uuid);
    }

    public function getRecipient()
    {
        return $this->details->getRecipient($this->uuid);
    }

    public function getSubscribedAt()
    {
        return $this->details->getSubscribedAt($this->uuid);
    }
}
