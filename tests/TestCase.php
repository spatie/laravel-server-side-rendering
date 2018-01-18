<?php

namespace Spatie\Ssr\Tests;

use Spatie\Ssr\SsrServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp()
    {
        parent::setUp();

        $this->app->instance('path.public', __DIR__.'/public');
        $this->app->config->set('ssr.node.temp_path', __DIR__.'/temp');
    }

    protected function getPackageProviders($app)
    {
        return [SsrServiceProvider::class];
    }
}
