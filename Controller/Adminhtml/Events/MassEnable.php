<?php

namespace Aligent\Webhooks\Controller\Adminhtml\Events;

use Aligent\Webhooks\Api\AsyncEventRepositoryInterface;
use Aligent\Webhooks\Model\AsyncEvent;
use Aligent\Webhooks\Model\ResourceModel\Webhook\Collection;
use Aligent\Webhooks\Model\ResourceModel\Webhook\CollectionFactory;
use Exception;
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
    private $asyncEventRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        AsyncEventRepositoryInterface $asyncEventRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->asyncEventRepository = $asyncEventRepository;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('async_events/events/index');

        $asyncEventCollection = $this->collectionFactory->create();
        $this->filter->getCollection($asyncEventCollection);
        $this->enableAsyncEvents($asyncEventCollection);

        return $resultRedirect;
    }

    private function enableAsyncEvents(Collection $asyncEventCollection)
    {
        $enabled = 0;
        $alreadyEnabled = 0;

        /** @var AsyncEvent $asyncEvent */
        foreach ($asyncEventCollection as $asyncEvent) {
            $alreadyEnabled++;
            if (!$asyncEvent->getStatus()) {
                try {
                    $asyncEvent->setStatus(true);
                    $this->asyncEventRepository->save($asyncEvent, false);
                    $alreadyEnabled--;
                    $enabled++;
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        }

        if ($enabled) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 event(s) have been enabled.', $enabled)
            );
        }

        if ($alreadyEnabled) {
            $this->messageManager->addNoticeMessage(
                __('A total of %1 event(s) are already enabled.', $alreadyEnabled)
            );
        }
    }
}
