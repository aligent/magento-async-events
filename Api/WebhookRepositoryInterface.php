<?php

namespace Aligent\Webhooks\Api;

use Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface WebhookRepositoryInterface
{
    /**
     * @param string $subscriptionId
     * @return Data\WebhookInterface
     */
    public function get(string $subscriptionId): Data\WebhookInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return WebhookSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param Data\WebhookInterface $webhook
     * @return Data\WebhookInterface
     */
    public function save(Data\WebhookInterface $webhook): Data\WebhookInterface;

    /**
     * @param string $subscriptionId
     * @param Data\WebhookUpdateInterface $webhook
     * @return Data\WebhookInterface
     */
    public function update(string $subscriptionId, Data\WebhookUpdateInterface $webhook): Data\WebhookInterface;
}
