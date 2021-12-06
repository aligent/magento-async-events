<?php

namespace Aligent\AsyncEvents\Api;

use Aligent\AsyncEvents\Api\Data\AsyncEventDisplayInterface;
use Aligent\AsyncEvents\Api\Data\AsyncEventInterface;
use Aligent\AsyncEvents\Api\Data\AsyncEventSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface AsyncEventRepositoryInterface
{
    /**
     * @param string $subscriptionId
     * @return \Aligent\AsyncEvents\Api\Data\AsyncEventDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(string $subscriptionId): AsyncEventDisplayInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aligent\AsyncEvents\Api\Data\AsyncEventSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): AsyncEventSearchResultsInterface;

    /**
     * @param \Aligent\AsyncEvents\Api\Data\AsyncEventInterface $asyncEvent
     * @param bool $checkResources
     * @return \Aligent\AsyncEvents\Api\Data\AsyncEventDisplayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    public function save(AsyncEventInterface $asyncEvent, bool $checkResources = true): AsyncEventDisplayInterface;
}
