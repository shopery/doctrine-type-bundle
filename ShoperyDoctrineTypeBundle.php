<?php

/**
 * This file is part of shopery/doctrine-type-bundle
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\Bundle\DoctrineTypeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShoperyDoctrineTypeBundle extends Bundle
{
    protected function createContainerExtension()
    {
        return new DependencyInjection\ShoperyDoctrineTypeExtension();
    }
}
