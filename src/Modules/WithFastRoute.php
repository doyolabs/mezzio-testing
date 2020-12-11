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

use Mezzio\Router\FastRouteRouter\ConfigProvider;

trait WithFastRoute
{
    public static function configureFastRoute(): void
    {
        static::addConfigProvider(ConfigProvider::class);
    }
}
