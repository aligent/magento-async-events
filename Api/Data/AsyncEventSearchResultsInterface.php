<?php


namespace Aligent\AsyncEvents\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AsyncEventSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Aligent\AsyncEvents\Api\Data\AsyncEventInterface[]
     */
    public function getItems();

    /**
     * @param \Aligent\AsyncEvents\Api\Data\AsyncEventInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
