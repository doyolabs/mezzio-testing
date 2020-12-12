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

use Doyo\Mezzio\Testing\Exception\WithConfigurationException;
use Laminas\ConfigAggregator\ArrayProvider;
use Psr\Container\ContainerInterface;

trait WithConfigurationFile
{
    protected static ?string $configDir = null;

    protected static string $configFilename    = 'config.php';
    protected static string $containerFilename = 'container.php';
    protected static string $pipelineFilename  = 'pipeline.php';
    protected static string $routesFilename    = 'routes.php';

    abstract protected function setupConfigFiles();

    /**
     * @throws WithConfigurationException when config directory not exists
     * @throws WithConfigurationException when configuration file not exists
     */
    final protected static function loadConfigFromFile(): void
    {
        $configDir = static::$configDir;

        if ( ! is_dir($configDir)) {
            throw WithConfigurationException::configDirNotExists($configDir);
        }

        $configFile = $configDir.'/'.static::$configFilename;
        if ( ! is_file($configFile) || ! is_readable($configFile)) {
            throw WithConfigurationException::configFileNotExists($configDir, static::$configFilename);
        }

        // reset default config providers
        static::$configProviders = [];
        $configs                 = require $configFile;
        $provider                = new ArrayProvider($configs);
        static::addConfigProvider($provider);
    }

    /**
     * @throws WithConfigurationException @throws when container configuration not exists
     *
     * @return ContainerInterface
     */
    final protected static function loadContainerFromFile(): ContainerInterface
    {
        $containerFile = static::$configDir.'/'.static::$containerFilename;

        if ( ! is_file($containerFile) || ! is_readable($containerFile)) {
            throw WithConfigurationException::containerFileNotExist(static::$configDir, static::$containerFilename);
        }

        return require $containerFile;
    }

    /**
     * @throws WithConfigurationException when pipeline configuration not exists
     */
    final protected static function loadPipelineFromFile(): callable
    {
        $pipelineFile =  static::$configDir.'/'.static::$pipelineFilename;

        if ( ! is_file($pipelineFile) || ! is_readable($pipelineFile)) {
            throw WithConfigurationException::pipelineFileNotExists(static::$configDir, static::$pipelineFilename);
        }

        return require $pipelineFile;
    }

    /**
     * @throws WithConfigurationException when routes configuration file not exists
     */
    final protected static function loadRoutesFromFile(): callable
    {
        $routesFile = static::$configDir.'/'.static::$routesFilename;

        if ( ! is_file($routesFile) || ! is_readable($routesFile)) {
            throw WithConfigurationException::routesFileNotExists(static::$configDir, static::$routesFilename);
        }

        return require $routesFile;
    }
}
