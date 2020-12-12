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

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Mezzio\Application;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Psr\Container\ContainerInterface;

trait WithContainer
{
    protected static ?ContainerInterface $container = null;

    protected static ?Application $app = null;

    protected static array $config = [
        'dependencies' => [],
    ];

    protected static array $configProviders = [
        \Laminas\HttpHandlerRunner\ConfigProvider::class,
        \Mezzio\ConfigProvider::class,
        \Mezzio\Router\ConfigProvider::class,
        \Laminas\Diactoros\ConfigProvider::class,
    ];

    protected static array $uses;

    /**
     * @param string|ArrayProvider|PhpFileProvider $provider
     */
    public static function addConfigProvider($provider): void
    {
        if ( ! \in_array($provider, static::$configProviders, true)) {
            static::$configProviders[] = $provider;
        }
    }

    protected static function configure(): void
    {
    }

    /**
     * @psalm-suppress UndefinedMethod
     */
    protected static function initialize(): void
    {
        $uses = static::$uses = array_flip(static::getClassUses(static::class));

        if (isset($uses[WithConfigurationFile::class])) {
            static::loadConfigFromFile();
        }

        if (isset($uses[WithFastRoute::class])) {
            static::configureFastRoute();
        }
        static::configure();

        static::initConfig();
        static::initTraits();
        static::initContainer();
    }

    /**
     * @psalm-suppress UndefinedMethod
     */
    protected static function initTraits(): void
    {
        $uses = static::$uses;

        if (isset($uses[WithLaminasServiceManager::class])) {
            static::setupServiceManager();
        }
    }

    protected static function initConfig(): void
    {
        $aggregator                 = new ConfigAggregator(static::$configProviders);
        static::$config             = $aggregator->getMergedConfig();
    }

    protected static function initContainer(): void
    {
        $container                  = static::$container;
        \assert(null !== $container);

        /** @var Application|null $app */
        $app                        = $container->get(Application::class);
        /** @var MiddlewareFactory|null $middlewareFactory */
        $middlewareFactory          = $container->get(MiddlewareFactory::class);

        \assert(null !== $app);
        \assert(null !== $middlewareFactory);
        static::configurePipeline($app, $middlewareFactory, $container);
    }

    protected static function configurePipeline(
        Application $app,
        MiddlewareFactory $factory,
        ContainerInterface $container
    ): void {
        $app->pipe(ErrorHandler::class);
        $app->pipe(ServerUrlMiddleware::class);
        $app->pipe(RouteMiddleware::class);
        $app->pipe(ImplicitHeadMiddleware::class);
        $app->pipe(ImplicitOptionsMiddleware::class);
        $app->pipe(MethodNotAllowedMiddleware::class);
        $app->pipe(UrlHelperMiddleware::class);
        $app->pipe(DispatchMiddleware::class);
        $app->pipe(NotFoundHandler::class);
    }

    protected static function configureRoutes(
        Application $app,
        MiddlewareFactory $factory,
        ContainerInterface $container
    ): void {
    }

    /**
     * @return mixed|Application
     */
    protected function getApplication()
    {
        return $this->getService(Application::class);
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    protected function getService(string $id)
    {
        \assert(null !== static::$container);

        return static::$container->get($id);
    }

    private static function getClassUses(string $class): array
    {
        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += static::traitUsesRecursive($class);
        }

        return array_unique($results);
    }

    private static function traitUsesRecursive(string $trait): array
    {
        $traits = class_uses($trait);

        foreach ($traits as $trait) {
            $traits = array_merge($traits, static::traitUsesRecursive($trait));
        }

        return $traits;
    }
}
