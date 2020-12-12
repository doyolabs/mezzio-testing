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

namespace Doyo\Mezzio\Testing\Tests\Modules;

use Doyo\Mezzio\Testing\Modules\WithFastRoute;
use Doyo\Mezzio\Testing\Modules\WithLaminasServiceManager;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\TestCase;

class WithFastRouteTest extends TestCase
{
    use WithFastRoute;
    use WithLaminasServiceManager;

    public function testConfiguration()
    {
        static::initialize();

        $router = $this->getService(RouterInterface::class);

        $this->assertNotNull($router);
        $this->assertInstanceOf(FastRouteRouter::class, $router);
    }
}
