<?php

namespace Aligent\Webhooks\Api;

use Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;

interface WebhookRepositoryInterface
{
    /**
     * @param string $subscriptionId
     * @return Data\WebhookInterface
     * @throws NoSuchEntityException
     */
    public function get(string $subscriptionId): Data\WebhookInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return WebhookSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param Data\WebhookInterface $webhook
     * @throws AlreadyExistsException|NoSuchEntityException
     * @return Data\WebhookInterface
     */
    public function save(Data\WebhookInterface $webhook): Data\WebhookInterface;
}
