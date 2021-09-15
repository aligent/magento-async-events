<?php

declare(strict_types=1);
namespace Aligent\Webhooks\Model;

use Aligent\Webhooks\Api\Data\WebhookSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class WebhookSearchResults extends SearchResults implements WebhookSearchResultsInterface
{
}