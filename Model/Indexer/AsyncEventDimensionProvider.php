<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer;

use Aligent\AsyncEvents\Model\ResourceModel\AsyncEvent\CollectionFactory as AsyncEventCollectionFactory;
use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Indexer\DimensionProviderInterface;
use SplFixedArray;
use Traversable;

class AsyncEventDimensionProvider implements DimensionProviderInterface
{
    /**
     * Name for asynchronous event dimension for multidimensional indexer
     * 'ae' - stands for 'asynchronous_event'
     */
    const DIMENSION_NAME = 'ae';

    /**
     * @var SplFixedArray
     */
    private $asyncEventDataIterator;

    /**
     * @var AsyncEventCollectionFactory
     */
    private $asyncEventCollectionFactory;

    /**
     * @var DimensionFactory
     */
    private $dimensionFactory;

    public function __construct(
        AsyncEventCollectionFactory $asyncEventCollectionFactory,
        DimensionFactory $dimensionFactory
    ) {
        $this->asyncEventCollectionFactory = $asyncEventCollectionFactory;
        $this->dimensionFactory = $dimensionFactory;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->getAsyncEvents() as $asyncEvent) {
            yield $this->dimensionFactory->create(self::DIMENSION_NAME, $asyncEvent);
        }
    }

    private function getAsyncEvents(): array
    {
        if ($this->asyncEventDataIterator === null) {
            $asyncEvents = $this->asyncEventCollectionFactory->create()
                ->addFieldToSelect('event_name')
                ->distinct(true)
                ->getColumnValues('event_name')
            ;

            $this->asyncEventDataIterator = $asyncEvents;
        }

        return $this->asyncEventDataIterator;
    }
}
