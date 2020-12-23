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
use Contao\SwbGasRegForm\ContaoSwbGasRegForm;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoSwbGasRegForm::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
