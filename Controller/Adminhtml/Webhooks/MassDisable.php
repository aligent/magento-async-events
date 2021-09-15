<?php

namespace Aligent\Webhooks\Controller\Adminhtml\Webhooks;

use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Aligent\Webhooks\Model\ResourceModel\Webhook;
use Magento\Ui\Component\MassAction\Filter;
use Aligent\Webhooks\Model\ResourceModel\Webhook\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;

class MassDisable extends Action implements HttpPostActionInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @var Filter
     */
    private Filter $filter;

    /**
     * @var WebhookRepositoryInterface
     */
    private WebhookRepositoryInterface $webhookRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        WebhookRepositoryInterface $webhookRepository
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        $this->webhookRepository = $webhookRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('webhooks/webhooks/index');

        $webhookCollection = $this->collectionFactory->create();
        $this->filter->getCollection($webhookCollection);
        $this->disableWebhooks($webhookCollection);

        return $resultRedirect;
    }

    private function disableWebhooks(Webhook\Collection $webhookCollection)
    {
        $disabled = 0;
        $alreadyDisabled = 0;

        /** @var \Aligent\Webhooks\Model\Webhook $webhook */
        foreach ($webhookCollection as $webhook) {
            $alreadyDisabled++;
            if ($webhook->getStatus()) {
                try {
                    $webhook->setStatus(false);
                    $this->webhookRepository->save($webhook, false);
                    $alreadyDisabled--;
                    $disabled++;
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        }

        if ($disabled) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 webhook(s) have been disabled.', $disabled)
            );
        }

        if ($alreadyDisabled) {
            $this->messageManager->addNoticeMessage(
                __('A total of %1 webhook(s) are already disabled.', $alreadyDisabled)
            );
        }
    }
}
