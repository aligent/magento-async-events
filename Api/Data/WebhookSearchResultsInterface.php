<?php


namespace Aligent\Webhooks\Api\Data;

interface WebhookSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Aligent\Webhooks\Api\Data\WebhookInterface[]
     */
    public function getItems();

    /**
     * @param \Aligent\Webhooks\Api\Data\WebhookInterface[] $items
     * @return $this
     */
    public function setItems($items);
}
