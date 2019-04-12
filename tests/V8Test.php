<?php

namespace Spatie\Ssr\Tests;

use Spatie\Ssr\Engines\V8;
use Spatie\Ssr\SsrServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class V8Test extends Orchestra
{
    public function setUp(): void
    {
        if (! extension_loaded('v8js')) {
            $this->markTestSkipped('The V8Js extension is not available.');
        }

        parent::setUp();

        $this->app->instance('path.public', __DIR__.'/public');
    }

    protected function getPackageProviders($app)
    {
        return [SsrServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('ssr.engine', V8::class);
    }

    /** @test */
    public function it_can_render_a_javascript_app()
    {
        $result = ssr('js/app-server.js')->enabled()->debug()->render();

        $this->assertEquals('<p>Hello, world!</p>', $result);
    }
}
