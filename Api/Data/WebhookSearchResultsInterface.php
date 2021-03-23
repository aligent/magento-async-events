<?php


namespace Aligent\Webhooks\Api\Data;

interface WebhookSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Aligent\Webhooks\Api\Data\WebhookDisplayInterface[]
     */
    public function getItems();

    /**
     * @param \Aligent\Webhooks\Api\Data\WebhookDisplayInterface[] $items
     * @return $this
     */
    public function setItems($items);
}
