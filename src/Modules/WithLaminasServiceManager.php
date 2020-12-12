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

use Laminas\ServiceManager\ServiceManager;

trait WithLaminasServiceManager
{
    use WithContainer;

    protected static function setupServiceManager(): void
    {
        $config                     = static::$config;
        $deps                       = $config['dependencies'];
        $deps['services']['config'] = $config;

        static::$container         = new ServiceManager($deps);
    }
}
