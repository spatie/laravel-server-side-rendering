<?php

namespace Spatie\Ssr;

use Spatie\Ssr\Engine;
use Spatie\Ssr\Renderer;
use Spatie\Ssr\Engines\Node;
use Spatie\Ssr\Resolvers\MixResolver;
use Illuminate\Support\ServiceProvider;

class SsrServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ssr.php' => config_path('ssr.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ssr.php', 'ssr');

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

        $this->app->resolving(
            Renderer::class,
            function (Renderer $serverRenderer) {
                return $serverRenderer
                    ->enabled($this->app->config->get('ssr.enabled'))
                    ->debug($this->app->config->get('ssr.debug'))
                    ->context('url', '/' . $this->app->request->path())
                    ->context($this->app->config->get('ssr.context'))
                    ->env($this->app->config->get('ssr.env'))
                    ->resolveEntryWith(new MixResolver($this->app->config->get('ssr.mix')));
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
