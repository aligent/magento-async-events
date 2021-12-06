<?php

declare(strict_types=1);
namespace Aligent\AsyncEvents\Model;

use Aligent\AsyncEvents\Api\Data\AsyncEventSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class AsyncEventSearchResults extends SearchResults implements AsyncEventSearchResultsInterface
{
}
