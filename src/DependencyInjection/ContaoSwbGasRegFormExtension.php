<?php

declare(strict_types=1);

/*
 * This file is part of contao-swbgasregform.
 *
 * (c) Erik Wegner
 *
 * @license AGPL-3.0-or-later
 */

namespace Contao\SwbGasRegForm\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoSwbGasRegFormExtension extends Extension
{
    public function load(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');
    }
}
