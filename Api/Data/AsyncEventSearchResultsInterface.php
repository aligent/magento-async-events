<?php


namespace Aligent\Webhooks\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AsyncEventSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Aligent\Webhooks\Api\Data\AsyncEventInterface[]
     */
    public function getItems();

    /**
     * @param \Aligent\Webhooks\Api\Data\AsyncEventInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
