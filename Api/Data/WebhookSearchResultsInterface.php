<?php


namespace Aligent\Webhooks\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface WebhookSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return WebhookInterface[]
     */
    public function getItems(): array;

    /**
     * @param WebhookInterface[] $items
     * @return $this
     */
    public function setItems(array $items): self;
}
