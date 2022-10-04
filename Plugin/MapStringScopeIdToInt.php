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
