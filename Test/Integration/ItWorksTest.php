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
     * @var AsyncEventRepositoryInterface|null
     */
    private ?AsyncEventRepositoryInterface $asyncEventRepository;

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
     * @magentoDataFixture Aligent_AsyncEvents::Test/_files/test_fixture.php
     */
    public function testGetById(): void
    {
        $expectedAsyncEvent = $this->asyncEventRepository->get(1);
        $this->assertEquals('example.event', $expectedAsyncEvent->getEventName());
    }
}
