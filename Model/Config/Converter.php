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
            $webhookResources = [];

            /** @var DOMNode $serviceConfig */
            foreach ($webhookConfig->childNodes as $child) {
                if ($child->nodeName === 'service') {
                    $webhookService = $this->convertServiceConfig($child);
                } elseif ($child->nodeName === 'resources') {
                    $webhookResources = $this->convertResourcesConfig($child);
                }
            }
            $output[mb_strtolower($hookName)] = array_merge($webhookService, $webhookResources);
        }

        return $output;
    }

    private function convertServiceConfig(DomNode $observerConfig): array
    {
        $output = [];

        $classAttribute = $observerConfig->attributes->getNamedItem('class');
        if (!$classAttribute || !$classAttribute->nodeValue) {
            throw new InvalidArgumentException('Attribute class is missing');
        }

        $methodAttribute = $observerConfig->attributes->getNamedItem('method');
        if (!$methodAttribute || !$methodAttribute->nodeValue) {
            throw new InvalidArgumentException('Attribute method is missing');
        }

        // check that the specified class/method exists and is public
        try {
            $serviceClass = new \ReflectionClass($classAttribute->nodeValue);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException(
                sprintf('Class %s does not exist', $classAttribute->nodeValue)
            );
        }
        try {
            $serviceMethod = $serviceClass->getMethod($methodAttribute->nodeValue);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException(
                sprintf(
                    'Method %s does not exist is class %s',
                    $methodAttribute->nodeValue,
                    $classAttribute->nodeValue
                )
            );
        }

        $output['class'] = $classAttribute->nodeValue;
        $output['method'] = $methodAttribute->nodeValue;

        return $output;
    }

    private function convertResourcesConfig(DOMNode $resourcesConfig): array
    {
        $resources = [];
        foreach ($resourcesConfig->childNodes as $resourceNode) {
            if ($resourceNode->nodeName === 'resource') {
                $resources[] = $resourceNode->nodeValue;
            }
        }

        return ['resources' => $resources];
    }
}
