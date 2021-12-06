<?php

namespace Aligent\Webhooks\Controller\Adminhtml\Events;

use Aligent\Webhooks\Api\AsyncEventRepositoryInterface;
use Aligent\Webhooks\Model\AsyncEvent;
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
    protected $collectionFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var AsyncEventRepositoryInterface
     */
    private $webhookRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        AsyncEventRepositoryInterface $asyncEventRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->webhookRepository = $asyncEventRepository;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('async_events/events/index');

        $webhookCollection = $this->collectionFactory->create();
        $this->filter->getCollection($webhookCollection);
        $this->enableWebhooks($webhookCollection);

        return $resultRedirect;
    }

    private function enableWebhooks(Collection $webhookCollection)
    {
        $enabled = 0;
        $alreadyEnabled = 0;

        /** @var AsyncEvent $webhook */
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