<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Ui\DataProvider;

use Aligent\AsyncEvents\Model\Details;
use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog\Collection;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Aligent\AsyncEvents\Model\ResourceModel\AsyncEventLog\CollectionFactory as AsyncEventLogCollectionFactory;

class AsyncEventsTrace extends AbstractDataProvider
{

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var Details
     */
    private $traceDetails;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param AsyncEventLogCollectionFactory $collectionFactory
     * @param Details $traceDetails
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        AsyncEventLogCollectionFactory $collectionFactory,
        Details $traceDetails,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->traceDetails = $traceDetails;
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $uuid = $this->request->getParam($this->requestFieldName);
        $details = $this->traceDetails->getDetails($uuid);

        return [
            $uuid => [
                'general' => current($details['traces'])
            ]
        ];
    }
}
