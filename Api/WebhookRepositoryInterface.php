<?php

namespace Aligent\Webhooks\Api;

interface WebhookRepositoryInterface
{
    /**
     * @param string $subscriptionId
     * @return \Aligent\Webhooks\Api\Data\WebhookDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($subscriptionId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface
     */
    public function getList($searchCriteria);

    /**
     * @param \Aligent\Webhooks\Api\Data\WebhookInterface $webhook
     * @return \Aligent\Webhooks\Api\Data\WebhookDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save($webhook);
}
