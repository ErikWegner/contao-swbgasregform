<?php

declare(strict_types=1);

/*
 * This file is part of contao-swbgasregform.
 *
 * (c) Erik Wegner
 *
 * @license AGPL-3.0-or-later
 */

namespace Contao\SwbGasRegForm\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Contao\SwbGasRegForm\ContaoSwbGasRegForm;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface {

    public function getBundles(ParserInterface $parser) {
        return [
                BundleConfig::create(ContaoSwbGasRegForm::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel) {
        return $resolver
            ->resolve(__DIR__ . '/../Resources/config/routing.yml')
            ->load(__DIR__ . '/../Resources/config/routing.yml')
        ;
    }

}
