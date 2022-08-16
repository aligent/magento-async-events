<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Indexer;

use Magento\Framework\App\ScopeInterface;
use Magento\Framework\DataObject;

/**
 * This data object class only exists for the sake of a ScopeInterface which delegates getId()
 * to a getName();
 *
 * The reason for this is that almost everything upstream (at least in the magento/module-elasticsearch-7) assumes
 * it is a store scope id when it is clearly written in a way that it will accept custom scopes and dimensions!
 *
 * For example vendor/magento/module-elasticsearch/Model/Indexer/IndexerHandler.php:113 will eventually call
 * vendor/magento/framework/App/Config.php:68 But it doesn't care if it's a string and returns null.
 *
 * For this exact reason AND the index names generated in Elasticsearch (magento2_async_event_sales.order.created_V1
 * instead of magento2_async_event_1_V1) are a bit nicer with string, this class exists and delegates 'id' to 'name'
 */
class AsyncEventScope extends DataObject implements ScopeInterface
{
    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getName();
    }

    public function setId($id)
    {
        $this->setName($id);
    }

    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return (string) $this->getData('code');
    }

    public function setCode(string $code)
    {
        $this->setData('code', $code);
    }

    /**
     * @inheritDoc
     */
    public function getScopeType(): string
    {
        return 'async_event';
    }

    /**
     * @inheritDoc
     */
    public function getScopeTypeName(): string
    {
        return 'Async Event';
    }

    public function getName(): string
    {
        return (string) $this->getData('name');
    }

    public function setName($name)
    {
        $this->setData('name', $name);
    }
}
