<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\WebhookDisplayInterface;
use Aligent\Webhooks\Api\Data\WebhookInterface;
use Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface;
use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Aligent\Webhooks\Model\Config as WebhookConfig;
use Aligent\Webhooks\Model\ResourceModel\Webhook as WebhookResource;
use Aligent\Webhooks\Model\ResourceModel\Webhook\CollectionFactory as WebhookCollectionFactory;
use Aligent\Webhooks\Api\Data\WebhookSearchResultsInterfaceFactory as SearchResultsFactory;

use DateTime;
use DateTimeInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class WebhookRepository implements WebhookRepositoryInterface
{
    /**
     * @var WebhookFactory
     */
    private  $webhookFactory;

    /**
     * @var WebhookResource
     */
    private  $webhookResource;

    /**
     * @var WebhookConfig
     */
    private  $webhookConfig;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * @var WebhookCollectionFactory
     */
    private $webhookCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @param WebhookFactory $webhookFactory
     * @param WebhookResource $webhookResource
     * @param WebhookConfig $webhookConfig
     * @param SearchResultsFactory $searchResultsFactory
     * @param WebhookCollectionFactory $webhookCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param EncryptorInterface $encryptor
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        WebhookFactory $webhookFactory,
        WebhookResource $webhookResource,
        WebhookConfig $webhookConfig,
        SearchResultsFactory $searchResultsFactory,
        WebhookCollectionFactory $webhookCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        EncryptorInterface $encryptor,
        AuthorizationInterface $authorization
    ) {
        $this->webhookFactory = $webhookFactory;
        $this->webhookResource = $webhookResource;
        $this->webhookConfig = $webhookConfig;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->webhookCollectionFactory = $webhookCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->encryptor = $encryptor;
        $this->authorization = $authorization;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $subscriptionId): WebhookDisplayInterface
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
    public function getList(SearchCriteriaInterface $searchCriteria): WebhookSearchResultsInterface
    {
        $collection = $this->webhookCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $webhooks = [];

        /** @var Webhook $webhookModel */
        foreach ($collection as $webhookModel) {
            $webhooks[] = $webhookModel;
        }

        return $this->searchResultsFactory->create()
            ->setItems($webhooks)
            ->setTotalCount($collection->getSize())
            ->setSearchCriteria($searchCriteria);
    }

    /**
     * {@inheritDoc}
     */
    public function save(WebhookInterface $webhook, bool $checkResources = true): WebhookDisplayInterface
    {
        if ($checkResources) {
            $this->validateResources($webhook);
        }

        if (!$webhook->getSubscriptionId()) {
            $webhook->setStatus(true);
            $webhook->setSubscribedAt((new DateTime())->format(DateTimeInterface::ATOM));
            $secretVerificationToken = $this->encryptor->encrypt($webhook->getVerificationToken());
            $webhook->setVerificationToken($secretVerificationToken);

        } else {
            if ($webhook->getStatus() === null) {
                throw new LocalizedException(__("Status is required"));
            }

            $newStatus = $webhook->getStatus();
            $newMetadata = $webhook->getMetadata();

            $webhook = $this->get($webhook->getSubscriptionId());
            $webhook->setStatus($newStatus);

            if ($newMetadata) {
                $webhook->setMetadata($newMetadata);
            }
        }

        $this->webhookResource->save($webhook);

        return $webhook;
    }

    /**
     * @param WebhookInterface $webhook
     * @return void
     */
    private function validateResources(WebhookInterface $webhook)
    {
        $configData = $this->webhookConfig->get($webhook->getEventName());
        $resources = $configData['resources'] ?? [];
        foreach ($resources as $resource) {
            if (!$this->authorization->isAllowed($resource)) {
                throw new AuthorizationException(
                    __(
                        "The consumer isn't authorized to access %resources.",
                        ['resources' => $resources]
                    )
                );
            }
        }
    }

}
