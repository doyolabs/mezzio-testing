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

use Exception;

class WithConfigurationException extends Exception
{
    public static function configDirNotExists(string $path): self
    {
        return new self(sprintf(
            'Config directory "%s" not exists.',
            $path
        ));
    }

    public static function configFileNotExists(string $dir, string $fileName): self
    {
        return new self(sprintf(
            'Configuration file "%s" not found in directory: "%s"',
            $fileName,
            $dir
        ));
    }

    public static function containerFileNotExist(string $dir, string $fileName): self
    {
        return new self(sprintf(
            'Container config file "%s" not found in directory: "%s"',
            $fileName,
            $dir
        ));
    }

    public static function pipelineFileNotExists(string $dir, string $fileName): self
    {
        return new self(sprintf(
            'Pipeline config file "%s" not found in directory: "%s"',
            $fileName,
            $dir
        ));
    }

    public static function routesFileNotExists(string $dir, string $fileName): self
    {
        return new self(sprintf(
            'Routes config file "%s" not found in directory: "%s"',
            $fileName,
            $dir
        ));
    }
}
