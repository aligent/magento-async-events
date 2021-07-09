<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\Webhooks\Model;

use Magento\Framework\Config\DataInterface;

class Config
{
    /**
     * @var DataInterface
     */
    protected DataInterface $_dataStorage;

    /**
     * @param DataInterface $dataStorage
     */
    public function __construct(DataInterface $dataStorage)
    {
        $this->_dataStorage = $dataStorage;
    }

    public function get($key)
    {
        return $this->_dataStorage->get($key, []);
    }
}
