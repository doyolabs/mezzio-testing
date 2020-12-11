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

namespace Doyo\Mezzio\Testing\Tests;

use Doyo\Mezzio\Testing\Modules\WithFastRoute;
use Doyo\Mezzio\Testing\Modules\WithLaminasServiceManager;
use Doyo\Mezzio\Testing\TestCase;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\RouterInterface;

class TestCaseTest extends TestCase
{
    use WithFastRoute;
    use WithLaminasServiceManager;

    public function testShouldCreateServiceManager()
    {
        $this->assertNotNull(static::$container);

        $fastRouter = $this->getService(RouterInterface::class);
        $this->assertInstanceOf(FastRouteRouter::class, $fastRouter);
    }
}
