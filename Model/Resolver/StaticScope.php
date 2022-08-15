<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Model\Resolver;

use Magento\Framework\App\ScopeResolverInterface;

class StaticScope implements ScopeResolverInterface
{
    public function getScope($scopeId = null)
    {
        return $scopeId;
    }

    public function getScopes()
    {
        return [];
    }
}
