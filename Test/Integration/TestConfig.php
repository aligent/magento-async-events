<?php

namespace Aligent\AsyncEvents\Test\Integration;

use Aligent\AsyncEvents\Model\Config;
use Magento\Directory\Api\CountryInformationAcquirerInterface;

class TestConfig extends Config
{
    public function get(string $key): array
    {
        return [
            "class" => CountryInformationAcquirerInterface::class,
            "method" => "getCountryInfo",
        ];
    }
}
