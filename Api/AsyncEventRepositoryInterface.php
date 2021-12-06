<?php

namespace Aligent\Webhooks\Api;

use Aligent\Webhooks\Api\Data\AsyncEventDisplayInterface;
use Aligent\Webhooks\Api\Data\AsyncEventInterface;
use Aligent\Webhooks\Api\Data\AsyncEventSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface AsyncEventRepositoryInterface
{
    /**
     * @param string $subscriptionId
     * @return \Aligent\Webhooks\Api\Data\AsyncEventDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(string $subscriptionId): AsyncEventDisplayInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aligent\Webhooks\Api\Data\AsyncEventSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): AsyncEventSearchResultsInterface;

    /**
     * @param \Aligent\Webhooks\Api\Data\AsyncEventInterface $webhook
     * @param bool $checkResources
     * @return \Aligent\Webhooks\Api\Data\AsyncEventDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function save(AsyncEventInterface $webhook, bool $checkResources = true): AsyncEventDisplayInterface;
}
