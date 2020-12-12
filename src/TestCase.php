<?php

/*
 * This file is part of the doyo/mezzio-testing project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Doyo\Mezzio\Testing;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;

/**
 * Class TestCase.
 *
 * @codeCoverageIgnore
 */
abstract class TestCase extends BaseTestCase
{
    protected static ?ContainerInterface $container = null;

    abstract protected static function initialize(): void;

    public static function setUpBeforeClass(): void
    {
        static::initialize();
    }

    public static function tearDownAfterClass(): void
    {
        static::$container = null;
    }
}
