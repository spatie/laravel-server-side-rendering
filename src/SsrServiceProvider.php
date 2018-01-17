<?php

namespace Spatie\Ssr;

use Illuminate\Support\ServiceProvider;
use Spatie\Ssr\Engine;
use Spatie\Ssr\Engines\Node;
use Spatie\Ssr\Renderer;
use Spatie\Ssr\Resolver;

class SsrServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/config/ssr.php' => config_path('ssr.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/ssr.php', 'ssr');

        $config = $this->app->config['server-renderer'];

        $this->app
            ->when(Node::class)
            ->needs('$nodePath')
            ->give($config['node.node_path']);

        $this->app
            ->when(Node::class)
            ->needs('$tempPath')
            ->give($config['node.temp_path']);

        $this->app->singleton(Engine::class, $config['engine']);
        $this->app->singleton(Resolver::class, $config['resolver']);

        $this->app->resolving(
            Renderer::class,
            function (Renderer $serverRenderer) use ($config) {
                return $serverRenderer
                    ->enabled($config['enabled'])
                    ->debug($config['debug'])
                    ->withContext('url', '/' . $this->app->request->path())
                    ->withContext($config['context'])
                    ->withEnv($config['env']);
            }
        );

        $this->app->alias(Renderer::class, 'ssr');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['ssr'];
    }
}
