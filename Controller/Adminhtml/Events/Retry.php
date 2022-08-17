<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Controller\Adminhtml\Events;

use Aligent\AsyncEvents\Service\AsyncEvent\RetryManager;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Result\PageFactory;

class Retry extends Action implements HttpPostActionInterface
{
    /**
     * Retry Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param RetryManager $retryManager
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly RetryManager $retryManager,
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct($context);
    }

    /**
     * Execute page load
     *
     * @return void
     */
    public function execute(): void
    {
        $data = $this->getRequest()->getPostValue()['general'];

        $this->retryManager->init(
            (int) $data['subscription_id'],
            $this->serializer->unserialize($data['serialized_data'])['data'],
            $data['uuid']
        );

        $this->_redirect($this->_redirect->getRefererUrl());
    }
}
