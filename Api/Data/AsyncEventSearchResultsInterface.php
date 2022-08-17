<?php

declare(strict_types=1);

namespace Aligent\AsyncEvents\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AsyncEventSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Getter for items
     *
     * @return AsyncEventDisplayInterface[]
     */
    public function getItems();

    /**
     * Setter for items
     *
     * @param AsyncEventDisplayInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
