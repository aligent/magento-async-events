<?php

namespace Aligent\AsyncEvents\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AsyncEventSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Aligent\AsyncEvents\Api\Data\AsyncEventDisplayInterface[]
     */
    public function getItems();

    /**
     * @param \Aligent\AsyncEvents\Api\Data\AsyncEventDisplayInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
