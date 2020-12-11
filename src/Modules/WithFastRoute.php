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

namespace Doyo\Mezzio\Testing\Modules;

use Doyo\Mezzio\Testing\Tests\Exception\FastRouteException;

trait WithFastRoute
{
    public static function configureFastRoute(): void
    {
        $class = 'Mezzio\Router\FastRouteRouter\ConfigProvider';
        if ( ! class_exists($class)) {
            FastRouteException::fastRouteNotInstalled();
        }
        static::addConfigProvider($class);
    }
}
