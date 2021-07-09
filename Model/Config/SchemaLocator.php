<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\Webhooks\Model\Config;

use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Module\Dir;

class SchemaLocator implements SchemaLocatorInterface
{

    /**
     * Path to corresponding XSD file with validation rules for merged configs
     *
     * @var string
     */
    private string $_schema;

    /**
     * Path to corresponding XSD file with validation rules for individual configs
     *
     * @var string
     */
    private string $_schemaFile;

    /**
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     */
    public function __construct(\Magento\Framework\Module\Dir\Reader $moduleReader)
    {
        $dir = $moduleReader->getModuleDir(Dir::MODULE_ETC_DIR, 'Aligent_Webhooks');
        $this->_schema = $dir . '/webhooks.xsd';
        $this->_schemaFile = $dir . '/webhooks.xsd';
    }

    /**
     * {@inheritdoc}
     */
    public function getSchema(): ?string
    {
        return $this->_schema;
    }

    /**
     * {@inheritdoc}
     */
    public function getPerFileSchema(): ?string
    {
        return $this->_schemaFile;
    }
}
