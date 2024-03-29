<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Api;

use Aligent\AsyncEvents\Api\Data\AsyncEventDisplayInterface;
use Aligent\AsyncEvents\Api\Data\AsyncEventInterface;
use Aligent\AsyncEvents\Api\Data\AsyncEventSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface AsyncEventRepositoryInterface
{
    /**
     * Get a single asynchronous event by id
     *
     * @param int $subscriptionId
     * @return AsyncEventDisplayInterface
     * @throws NoSuchEntityException
     */
    public function get(int $subscriptionId): AsyncEventDisplayInterface;

    /**
     * Get a list of asynchronous events by search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return AsyncEventSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): AsyncEventSearchResultsInterface;

    /**
     * Save an asynchronous event
     *
     * @param AsyncEventInterface $asyncEvent
     * @param bool $checkResources
     * @return AsyncEventDisplayInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     * @throws AuthorizationException
     */
    public function save(AsyncEventInterface $asyncEvent, bool $checkResources = true): AsyncEventDisplayInterface;
}
