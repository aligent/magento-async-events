<?php

namespace Aligent\Webhooks\Api;

use Aligent\Webhooks\Api\Data\WebhookDisplayInterface;
use Aligent\Webhooks\Api\Data\WebhookInterface;
use Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface WebhookRepositoryInterface
{
    /**
     * @param string $subscriptionId
     * @return \Aligent\Webhooks\Api\Data\WebhookDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(string $subscriptionId): WebhookDisplayInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): WebhookSearchResultsInterface;

    /**
     * @param \Aligent\Webhooks\Api\Data\WebhookInterface $webhook
     * @param bool $checkResources
     * @return \Aligent\Webhooks\Api\Data\WebhookDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function save(WebhookInterface $webhook, bool $checkResources = true): WebhookDisplayInterface;
}
