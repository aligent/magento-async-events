<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\Webhooks\Model\Config;

use DOMNode;
use InvalidArgumentException;
use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{

    public function convert($source): array
    {
        $output = [];
        $webhooks = $source->getElementsByTagName('webhook');

        /** @var DOMNode $webhookConfig */
        foreach ($webhooks as $webhookConfig) {
            $hookName = $webhookConfig->attributes->getNamedItem('hook_name')->nodeValue;

            $webhookService = [];

            /** @var DOMNode $serviceConfig */
            foreach ($webhookConfig->childNodes as $serviceConfig) {
                if ($serviceConfig->nodeName != 'service' || $serviceConfig->nodeType != XML_ELEMENT_NODE) {
                    continue;
                }

                $webhookServiceMethodNode = $serviceConfig->attributes->getNamedItem('class');
                if (!$webhookServiceMethodNode) {
                    throw new InvalidArgumentException('Attribute class is missing');
                }

                $webhookServiceMethodNode = $serviceConfig->attributes->getNamedItem('method');
                if (!$webhookServiceMethodNode) {
                    throw new InvalidArgumentException('Attribute method is missing');
                }

                $webhookService = $this->convertServiceConfig($serviceConfig);
            }
            $output[mb_strtolower($hookName)] = $webhookService;
        }

        return $output;
    }

    private function convertServiceConfig($observerConfig): array
    {
        $output = [];
        /** Parse class configuration */
        $classAttribute = $observerConfig->attributes->getNamedItem('class');
        if ($classAttribute) {
            $output['class'] = $classAttribute->nodeValue;
        }

        /** Parse instance method configuration */
        $methodAttribute = $observerConfig->attributes->getNamedItem('method');
        if ($methodAttribute) {
            $output['method'] = $methodAttribute->nodeValue;
        }

        return $output;
    }
}
