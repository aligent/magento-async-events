<?php

namespace Aligent\Webhooks\Api;

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
     * @return \Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param Data\WebhookInputInterface $webhook
     * @return Data\WebhookInterface
     */
    public function save(Data\WebhookInputInterface $webhook): Data\WebhookInterface;

    /**
     * @param string $subscriptionId
     * @param Data\WebhookUpdateInterface $webhook
     * @return Data\WebhookInterface
     */
    public function update(string $subscriptionId, Data\WebhookUpdateInterface $webhook): Data\WebhookInterface;
}
