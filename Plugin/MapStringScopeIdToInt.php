<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Plugin;

use Magento\Search\Model\ResourceModel\SynonymReader;

class MapStringScopeIdToInt
{
    /**
     * Remap string to int
     *
     * From Magento version 2.4.5, a new synonym filter is introduced. This is strictly typed to be an int and will
     * fail with a PHP Type error since this module's scope is a string (the async event name). Since we do not care
     * about stemming and synonyms as we are literally just using it for the Lucene Query Syntax we can safely ignore
     * this and remap and provide a fake store view id of 0.
     *
     * @see vendor/magento/module-elasticsearch/Model/Adapter/Index/Builder.php:63
     * @param SynonymReader $subject
     * @param int|string $storeViewId
     * @return array
     */
    public function beforeGetAllSynonymsForStoreViewId(SynonymReader $subject, $storeViewId): array
    {
        if (!is_numeric($storeViewId) && is_string($storeViewId)) {
            $storeViewId = 0;
        }

        return [$storeViewId];
    }
}
