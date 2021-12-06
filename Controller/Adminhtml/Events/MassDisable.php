<?php

namespace Aligent\Webhooks\Controller\Adminhtml\Events;

use Aligent\Webhooks\Api\AsyncEventRepositoryInterface;
use Aligent\Webhooks\Model\AsyncEvent;
use Aligent\Webhooks\Model\ResourceModel\Webhook\Collection;
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
        AsyncEventRepositoryInterface $webhookRepository
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
        $resultRedirect->setPath('async_events/events/index');

        $webhookCollection = $this->collectionFactory->create();
        $this->filter->getCollection($webhookCollection);
        $this->disableWebhooks($webhookCollection);

        return $resultRedirect;
    }

    private function disableWebhooks(Collection $webhookCollection)
    {
        $disabled = 0;
        $alreadyDisabled = 0;

        /** @var AsyncEvent $webhook */
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
