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

use Doyo\Mezzio\Testing\Exception\WithConfigurationException;
use Doyo\Mezzio\Testing\Modules\WithConfigurationFile;
use Doyo\Mezzio\Testing\Modules\WithLaminasServiceManager;
use Laminas\ConfigAggregator\ArrayProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class WithConfigurationFileTest extends TestCase
{
    use WithConfigurationFile;
    use WithLaminasServiceManager;

    private ?string $tempDir = null;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir().'/doyo/mezzio-testing';
        if ( ! is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0777, true);
        }

        $content = <<<EOC
<?php

return [];
EOC;

        $container = <<<EOC
<?php

return new Laminas\ServiceManager\ServiceManager([]);
EOC;

        $callable = <<<EOC
<?php

return function(){
};

EOC;

        file_put_contents($this->tempDir.'/config.php', $content);
        file_put_contents($this->tempDir.'/container.php', $container);
        file_put_contents($this->tempDir.'/pipeline.php', $callable);
        file_put_contents($this->tempDir.'/routes.php', $callable);
    }

    protected function setupConfigFiles()
    {
        static::$configDir = __DIR__.'/../Resources/sandbox/config';
    }

    public function testThrowsExceptionWhenConfigDirNotExists()
    {
        static::$configDir = '/not/exists';

        $e = WithConfigurationException::configDirNotExists('/not/exists');
        $this->expectException(WithConfigurationException::class);
        $this->expectExceptionMessage($e->getMessage());
        static::loadConfigFromFile();
    }

    public function testLoadConfigFile()
    {
        static::$configDir = $this->tempDir;
        static::loadConfigFromFile();

        $config = static::$configProviders;
        $this->assertIsArray($config);
        $this->assertNotEmpty($config);
        $this->assertInstanceOf(ArrayProvider::class, $config[0]);
    }

    public function testThrowsExceptionWhenConfigFileNotExists()
    {
        static::$configDir = $this->tempDir;
        $configFile        = static::$configDir.'/config.php';
        if (is_file($configFile)) {
            unlink($configFile);
        }

        $e = WithConfigurationException::configFileNotExists(static::$configDir, 'config.php');
        $this->expectException(WithConfigurationException::class);
        $this->expectExceptionMessage($e->getMessage());

        static::loadConfigFromFile();
    }

    public function testLoadContainerFromFile()
    {
        static::$configDir = $this->tempDir;

        $container = static::loadContainerFromFile();

        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    public function testThrowsExceptionWhenContainerFileNotExists()
    {
        static::$configDir = $this->tempDir;
        $configFile        = static::$configDir.'/container.php';
        if (is_file($configFile)) {
            unlink($configFile);
        }

        $e = WithConfigurationException::containerFileNotExist(static::$configDir, 'container.php');
        $this->expectException(WithConfigurationException::class);
        $this->expectExceptionMessage($e->getMessage());

        static::loadContainerFromFile();
    }

    public function testLoadPipelineFromFile()
    {
        static::$configDir = $this->tempDir;

        $pipeline = static::loadPipelineFromFile();

        $this->assertIsCallable($pipeline);
    }

    public function testThrowExceptionWhenPipelineFileNotExists()
    {
        static::$configDir = $this->tempDir;
        $configFile        = static::$configDir.'/pipeline.php';
        if (is_file($configFile)) {
            unlink($configFile);
        }

        $e = WithConfigurationException::pipelineFileNotExists(static::$configDir, 'pipeline.php');
        $this->expectException(WithConfigurationException::class);
        $this->expectExceptionMessage($e->getMessage());

        static::loadPipelineFromFile();
    }

    public function testLoadRoutesFromFile()
    {
        static::$configDir = $this->tempDir;

        $routes = static::loadRoutesFromFile();

        $this->assertIsCallable($routes);
    }

    public function testThrowExceptionWhenRoutesFileNotExists()
    {
        static::$configDir = $this->tempDir;
        $configFile        = static::$configDir.'/routes.php';
        if (is_file($configFile)) {
            unlink($configFile);
        }

        $e = WithConfigurationException::routesFileNotExists(static::$configDir, 'routes.php');
        $this->expectException(WithConfigurationException::class);
        $this->expectExceptionMessage($e->getMessage());

        static::loadRoutesFromFile();
    }
}
