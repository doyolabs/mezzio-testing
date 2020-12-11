<?php

/*
 * This file is part of the Template project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Template\Tests;

use PHPUnit\Framework\TestCase;
use Template\Hello;

class HelloTest extends TestCase
{
    public function testHelloWorld()
    {
        $this->assertSame('Hello World', Hello::world());
    }
}
