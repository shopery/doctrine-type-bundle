<?php

/**
 * This file is part of shopery/doctrine-type-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\DoctrineTypeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ShoperyDoctrineTypeExtension extends Extension implements PrependExtensionInterface
{
    const ALIAS = 'shopery_doctrine_type';

    public function load(array $configs, ContainerBuilder $container)
    {
    }

    public function getAlias()
    {
        return self::ALIAS;
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = $this->getBundleConfiguration($container);
    }

    private function getBundleConfiguration(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig(self::ALIAS);

        $configuration = new Configuration(self::ALIAS);
        $config = $this->processConfiguration($configuration, $configs);

        return $container->getParameterBag()->resolveValue($config);
    }
}
