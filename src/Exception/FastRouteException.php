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

namespace Doyo\Mezzio\Testing\Exception;

class FastRouteException extends \Exception
{
    public static function fastRouteNotInstalled()
    {
        return new self(
            'Please install mezzio/mezzio-fastroute before using WithFastRoute trait.'
        );
    }
}
