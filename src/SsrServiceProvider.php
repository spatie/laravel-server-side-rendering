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
        $this->mergeConfigFrom(__DIR__ . '/../config/ssr.php', 'ssr');

        $this->app
            ->when(Node::class)
            ->needs('$nodePath')
            ->give(function () {
                return $this->app->config->get('ssr.node.node_path');
            });

        $this->app
            ->when(Node::class)
            ->needs('$tempPath')
            ->give(function () {
                return $this->app->config->get('ssr.node.temp_path');
            });

        $this->app->singleton(Engine::class, $this->app->config->get('ssr.engine'));
        $this->app->singleton(Resolver::class, $this->app->config->get('ssr.resolver'));

        $this->app->resolving(
            Renderer::class,
            function (Renderer $serverRenderer) {
                return $serverRenderer
                    ->enabled($this->app->config->get('ssr.enabled'))
                    ->debug($this->app->config->get('ssr.debug'))
                    ->withContext('url', '/' . $this->app->request->path())
                    ->withContext($this->app->config->get('ssr.context'))
                    ->withEnv($this->app->config->get('ssr.env'));
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
