<?php

namespace Aligent\Webhooks\Controller\Adminhtml\Webhooks;

use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Aligent\Webhooks\Model\ResourceModel\Webhook\Collection;
use Aligent\Webhooks\Model\ResourceModel\Webhook\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Ui\Component\MassAction\Filter;

class MassEnable extends Action implements HttpPostActionInterface
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
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->webhookRepository = $webhookRepository;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('webhooks/webhooks/index');

        $webhookCollection = $this->collectionFactory->create();
        $this->filter->getCollection($webhookCollection);
        $this->enableWebhooks($webhookCollection);

        return $resultRedirect;
    }

    private function enableWebhooks(Collection $webhookCollection)
    {
        $enabled = 0;
        $alreadyEnabled = 0;

        /** @var \Aligent\Webhooks\Model\Webhook $webhook */
        foreach ($webhookCollection as $webhook) {
            $alreadyEnabled++;
            if (!$webhook->getStatus()) {
                try {
                    $webhook->setStatus(true);
                    $this->webhookRepository->save($webhook, false);
                    $alreadyEnabled--;
                    $enabled++;
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        }

        if ($enabled) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 webhook(s) have been enabled.', $enabled)
            );
        }

        if ($alreadyEnabled) {
            $this->messageManager->addNoticeMessage(
                __('A total of %1 webhook(s) are already enabled.', $alreadyEnabled)
            );
        }
    }
}
