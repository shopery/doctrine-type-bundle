<?php

/**
 * This file is part of shopery/doctrine-type-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\DoctrineTypeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Shopery\Bundle\DoctrineTypeBundle\Iterator\ClassExtractorIterator;
use Shopery\Bundle\DoctrineTypeBundle\Iterator\DoctrineTypeIterator;

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
        $types = $this->findTypes($config['paths']);

        $container->prependExtensionConfig('doctrine', [
            'dbal' => [
                'types' => iterator_to_array($types),
            ]
        ]);
    }

    private function getBundleConfiguration(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig(self::ALIAS);

        $configuration = new Configuration(self::ALIAS);
        $config = $this->processConfiguration($configuration, $configs);

        return $container->getParameterBag()->resolveValue($config);
    }

    private function findTypes(array $paths)
    {
        $types = new \AppendIterator();

        foreach ($paths as $path) {
            $finder = Finder::create()->in($path['path']);
            $finder->name($path['match']);

            $iterator = new ClassExtractorIterator($finder);
            $iterator = new DoctrineTypeIterator($iterator, $path['suffix']);

            $types->append(new \IteratorIterator($iterator));
        }

        return $types;
    }
}
