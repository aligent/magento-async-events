<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Test\Api;

use Magento\Framework\Webapi\Rest\Request;
use Magento\TestFramework\TestCase\WebapiAbstract;

class ItWorksTest extends WebapiAbstract
{
    public function testBasicRoutingExplicitPath()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/async_event/3',
                'httpMethod' => Request::HTTP_METHOD_GET,
            ]
        ];

        $item = $this->_webApiCall($serviceInfo, [
            'searchCriteria' => ''
        ]);

        var_dump($item);

        $this->assertTrue(true);
    }
}
