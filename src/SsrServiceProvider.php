<?php

namespace Spatie\Ssr;

use Illuminate\Support\ServiceProvider;
use Spatie\Ssr\Engines\Node;
use Spatie\Ssr\Engines\V8;
use Spatie\Ssr\Resolvers\MixResolver;

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

        $this->app->singleton(Node::class, function ($app) {
            return new Node(
                $app->config->get('ssr.node.node_path'),
                $app->config->get('ssr.node.temp_path')
            );
        });

        $this->app->singleton(V8::class, function () {
            return new V8(new \V8Js());
        });

        $this->app->bind(Engine::class, function ($app) {
            return $app->make($app->config->get('ssr.engine'));
        });

        $this->app->resolving(
            Renderer::class,
            function (Renderer $serverRenderer, $app) {
                return $serverRenderer
                    ->enabled($app->config->get('ssr.enabled'))
                    ->debug($app->config->get('ssr.debug'))
                    ->context('url', $app->request->getRequestUri())
                    ->context($app->config->get('ssr.context'))
                    ->env($app->config->get('ssr.env'))
                    ->resolveEntryWith(new MixResolver($app->config->get('ssr.mix')));
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
