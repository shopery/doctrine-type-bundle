<?php

/**
 * This file is part of shopery/doctrine-type-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\DoctrineTypeBundle\Iterator;

use Traversable;

class DoctrineTypeIterator implements \IteratorAggregate
{
    private $innerIterator;
    private $suffix;

    public function __construct(Traversable $iterator, string $suffix)
    {
        $this->innerIterator = $iterator;
        $this->suffix = $suffix;
    }

    public function getIterator()
    {
        foreach ($this->innerIterator as $className) {
            $typeName = $this->getTypeName($className);

            yield $typeName => $className;
        }
    }

    private function getTypeName(string $fullClassName)
    {
        $suffixLength = strlen($this->suffix);
        if (substr_compare($fullClassName, $this->suffix, -$suffixLength, $suffixLength) !== 0) {
            throw new \InvalidArgumentException($fullClassName);
        }

        $className = substr($fullClassName, strrpos($fullClassName, '\\') + 1, -$suffixLength);

        return strtolower(preg_replace('~(?<!^)[A-Z]~', '_$0', $className));
    }
}
