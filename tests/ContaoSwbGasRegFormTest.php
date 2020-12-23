<?php

declare(strict_types=1);

/*
 * This file is part of contao-swbgasregform.
 *
 * (c) Erik Wegner
 *
 * @license AGPL-3.0-or-later
 */

namespace Contao\SwbGasRegForm\Tests;

use Contao\SwbGasRegForm\ContaoSwbGasRegForm;
use PHPUnit\Framework\TestCase;

class ContaoSwbGasRegFormTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $bundle = new ContaoSwbGasRegForm();

        $this->assertInstanceOf('Contao\SwbGasRegForm\ContaoSwbGasRegForm', $bundle);
    }
}
