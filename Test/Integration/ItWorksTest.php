<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Test\Integration;

use Aligent\AsyncEvents\Api\AsyncEventRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class ItWorksTest extends TestCase
{
    /**
     * @var AsyncEventRepositoryInterface
     */
    private AsyncEventRepositoryInterface $asyncEventRepository;

    protected function setUp(): void
    {
        $this->asyncEventRepository = Bootstrap::getObjectManager()->get(AsyncEventRepositoryInterface::class);
    }

    public function testItWorks()
    {
        $this->assertEquals(true, true);
        $this->assertEquals('Double checking this works', 'Double checking this works');
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testGetById(): void
    {
        $this->assertEquals(true, true);
    }
}