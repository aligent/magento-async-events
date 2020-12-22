<?php

namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data;
use Aligent\Webhooks\Api\WebhookRepositoryInterface;
use Aligent\Webhooks\Model\ResourceModel\Webhook as WebhookResource;

use Magento\Framework\Exception\AlreadyExistsException;
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
     * WebhookRepository constructor.
     * @param WebhookFactory $webhookFactory
     * @param WebhookResource $webhookResource
     */
    public function __construct(WebhookFactory $webhookFactory, WebhookResource $webhookResource)
    {
        $this->webhookFactory = $webhookFactory;
        $this->webhookResource = $webhookResource;
    }

    /**
     * @param Data\WebhookInputInterface $webhookInput
     * @return Data\WebhookInterface
     * @throws AlreadyExistsException
     */
    public function save(Data\WebhookInputInterface $webhookInput): Data\WebhookInterface
    {
        $webhook = $this->webhookFactory->create();
        $webhook->setStatus(true);
        $webhook->setSubscribedAt(new \DateTime());

        $webhook->setEventName($webhookInput->getEventName());
        $webhook->setRecipientUrl($webhookInput->getRecipientUrl());
        $webhook->setVerificationToken($webhookInput->getVerificationToken());
        $webhook->setVerificationToken($webhookInput->getVerificationToken());
        $this->webhookResource->save($webhook);

        return $webhook;
    }

    /**
     * @param string $subscriptionId
     * @return Data\WebhookInterface
     * @throws NoSuchEntityException
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
     * @param string $subscriptionId
     * @param Data\WebhookUpdateInterface $webhookUpdate
     * @return Data\WebhookInterface
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     */
    public function update(string $subscriptionId, Data\WebhookUpdateInterface $webhookUpdate): Data\WebhookInterface
    {
        $webhook = $this->get($subscriptionId);

        if ($eventName = $webhookUpdate->getEventName()) {
            $webhook->setEventName($eventName);
        }

        if ($recipientUrl = $webhookUpdate->getRecipientUrl()) {
            $webhook->setRecipientUrl($recipientUrl);
        }

        if ($verificationToken = $webhookUpdate->getVerificationToken()) {
            $webhook->setVerificationToken($verificationToken);
        }

        $this->webhookResource->save($webhook);

        return $webhook;
    }
}

