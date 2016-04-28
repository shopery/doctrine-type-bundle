<?php

/**
 * This file is part of shopery/doctrine-type-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\DoctrineTypeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $alias;

    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root($this->alias);

        $root
            ->children()
                ->arrayNode('paths')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')
                                ->isRequired()
                            ->end()
                            ->scalarNode('match')->end()
                            ->scalarNode('suffix')->end()
                        ->end()

                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($v) {
                                return [
                                    'path' => $v,
                                ];
                            })
                        ->end()

                        ->validate()
                            ->ifTrue(function ($v) {
                                return !isset($v['suffix']);
                            })
                            ->then(function ($v) {
                                $v['suffix'] = 'OrmType';

                                return $v;
                            })
                        ->end()

                        ->validate()
                            ->ifTrue(function ($v) {
                                return !isset($v['match']);
                            })
                            ->then(function ($v) {
                                $v['match'] = '*' . $v['suffix'] . '.php';

                                return $v;
                            })
                        ->end()

                    ->end()
                ->end()
            ->end()
        ->end();

        return $builder;
    }
}
