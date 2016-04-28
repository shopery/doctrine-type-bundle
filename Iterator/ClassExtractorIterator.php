<?php

/**
 * This file is part of shopery/doctrine-type-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\DoctrineTypeBundle\Iterator;

use Traversable;

class ClassExtractorIterator implements \IteratorAggregate
{
    private $innerIterator;

    public function __construct(Traversable $innerIterator)
    {
        $this->innerIterator = $innerIterator;
    }

    public function getIterator()
    {
        foreach ($this->innerIterator as $filename) {
            yield from $this->extractClasses($filename);
        }
    }

    private function extractClasses($filename)
    {
        $state = false;
        $namespace = '';

        foreach (token_get_all(file_get_contents($filename)) as $token) {
            if ($token === ';') {
                $state = false;
            }

            if (!is_array($token)) {
                continue;
            }

            switch ($token[0]) {
                case T_NAMESPACE:
                    $namespace = '';
                    $state = T_NAMESPACE;
                    break;

                case T_CLASS:
                    $state = T_CLASS;
                    break;

                case T_STRING:
                    switch ($state) {
                        case T_CLASS:
                            yield $namespace . $token[1];
                            $state = T_STRING;
                            break;
                        case T_NAMESPACE:
                            $namespace .= $token[1] . '\\';
                            break;
                    }
                    break;
            }
        }
    }
}
