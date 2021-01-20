<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data;
use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Aligent\Webhooks\Model\ResourceModel\Webhook as WebhookResource;
use Aligent\Webhooks\Model\ResourceModel\Webhook\CollectionFactory as WebhookCollectionFactory;
use Aligent\Webhooks\Api\Data\WebhookSearchResultsInterfaceFactory as SearchResultsFactory;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class WebhookRepository implements WebhookRepositoryInterface
{
    /**
     * @var WebhookFactory
     */
    private WebhookFactory $webhookFactory;

    /**
     * @var WebhookResource
     */
    private WebhookResource $webhookResource;

    /**
     * @var SearchResultsFactory
     */
    private SearchResultsFactory $searchResultsFactory;

    /**
     * @var WebhookCollectionFactory
     */
    private WebhookCollectionFactory $webhookCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @param WebhookFactory $webhookFactory
     * @param WebhookResource $webhookResource
     * @param SearchResultsFactory $searchResultsFactory
     * @param WebhookCollectionFactory $webhookCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        WebhookFactory $webhookFactory,
        WebhookResource $webhookResource,
        SearchResultsFactory $searchResultsFactory,
        WebhookCollectionFactory $webhookCollectionFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->webhookFactory = $webhookFactory;
        $this->webhookResource = $webhookResource;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->webhookCollectionFactory = $webhookCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $subscriptionId): Data\WebhookInterface
    {
        $webhook = $this->webhookFactory->create();
        $this->webhookResource->load($webhook, $subscriptionId);

        if (!$webhook->getId()) {
            throw new NoSuchEntityException(__('Webhook with subscription ID %1 does not exist', $subscriptionId));
        }

        return $webhook;
    }

    /**
     * {@inheritDoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->webhookCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $webhooks = [];

        /** @var \Aligent\Webhooks\Model\Webhook $webhookModel */
        foreach ($collection as $webhookModel) {
            $webhooks[] = $webhookModel;
        }

        return $this->searchResultsFactory->create()
            ->setItems($webhooks)
            ->setTotalCount($collection->getSize())
            ->setSearchCriteria($searchCriteria)
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function save(Data\WebhookInterface $webhook): Data\WebhookInterface
    {
        if (!$webhook->getSubscriptionId()) {
            $webhook->setStatus(true);
            $webhook->setSubscribedAt((new \DateTime())->format(\DateTime::ISO8601));
        } else {
            $newStatus = $webhook->getStatus();

            $webhook = $this->get($webhook->getSubscriptionId());
            $webhook->setStatus($newStatus);
        }

        $this->webhookResource->save($webhook);

        return $webhook;
    }
}
