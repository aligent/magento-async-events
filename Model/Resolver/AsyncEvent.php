<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Resolver;

use Aligent\AsyncEvents\Model\Indexer\AsyncEventScopeFactory;
use Aligent\AsyncEvents\Model\ResourceModel\AsyncEvent\CollectionFactory as AsyncEventCollectionFactory;
use Magento\Framework\App\ScopeResolverInterface;

class AsyncEvent implements ScopeResolverInterface
{
    /**
     * @var AsyncEventCollectionFactory
     */
    private $asyncEventCollectionFactory;

    /**
     * @var AsyncEventScopeFactory
     */
    private $asyncEventScopeFactory;

    /**
     * @param AsyncEventCollectionFactory $asyncEventCollectionFactory
     * @param AsyncEventScopeFactory $asyncEventScopeFactory
     */
    public function __construct(
        AsyncEventCollectionFactory $asyncEventCollectionFactory,
        AsyncEventScopeFactory $asyncEventScopeFactory
    ) {
        $this->asyncEventCollectionFactory = $asyncEventCollectionFactory;
        $this->asyncEventScopeFactory = $asyncEventScopeFactory;
    }

    /**
     * @inheritDoc
     */
    public function getScope($scopeId = null)
    {
        $asyncEventScope = $this->asyncEventScopeFactory->create();
        $asyncEventScope->setId($scopeId);

        return $asyncEventScope;
    }

    /**
     * @inheritDoc
     */
    public function getScopes(): array
    {
        $asyncEvents = $this->asyncEventCollectionFactory->create()->getData();

        $scope = [];
        foreach ($asyncEvents as $asyncEvent) {
            $asyncEventScope = $this->asyncEventScopeFactory->create([
                'data' => $asyncEvent
            ]);

            $asyncEventScope->setId($asyncEvent['event_name']);

            $scope[] = $asyncEventScope;
        }

        return $scope;
    }
}
