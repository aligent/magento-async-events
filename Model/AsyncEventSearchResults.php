<?php

declare(strict_types=1);
namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\AsyncEventSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class AsyncEventSearchResults extends SearchResults implements AsyncEventSearchResultsInterface
{
}
