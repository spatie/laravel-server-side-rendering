<?php

namespace Spatie\Ssr\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Ssr\SsrServiceProvider;

class NodeTest extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->usePublicPath(__DIR__.'/public');
    }

    protected function getPackageProviders($app)
    {
        return [SsrServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('ssr.node.temp_path', __DIR__.'/temp');
    }

    /** @test */
    public function it_can_render_a_javascript_app()
    {
        $result = ssr('js/app-server.js')->enabled()->debug()->render();

        $this->assertEquals('<p>Hello, world!</p>', $result);
    }
}
