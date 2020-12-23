<?php


namespace Aligent\Webhooks\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface WebhookSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Aligent\Webhooks\Api\Data\WebhookInterface[]
     */
    public function getItems(): array;

    /**
     * @param \Aligent\Webhooks\Api\Data\WebhookInterface[] $items
     * @return $this
     */
    public function setItems(array $items): self;
}
