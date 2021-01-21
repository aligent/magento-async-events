<?php


namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Model\ResourceModel\WebhookLog as WebhookResource;

class WebhookLogRepository
{

    /**
     * @var WebhookLogFactory
     */
    private WebhookLogFactory $webhookLogFactory;

    /**
     * @var WebhookResource
     */
    private WebhookResource $webhookLogResource;

    public function __construct(
        WebhookLogFactory $webhookLogFactory,
        WebhookResource $webhookLogResource
    ) {
        $this->webhookLogFactory = $webhookLogFactory;
        $this->webhookLogResource = $webhookLogResource;
    }

    /**
     * @param WebhookLog $webhookLog
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save($webhookLog)
    {
        $this->webhookLogResource->save($webhookLog);
    }
}

