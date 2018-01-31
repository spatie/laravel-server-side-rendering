<?php

namespace Spatie\Ssr\Tests;

class SsrTest extends TestCase
{
    /** @test */
    public function it_can_render_a_javascript_app()
    {
        $result = ssr('js/app.js')->enabled()->debug()->render();

        $this->assertEquals('<p>Hello, world!</p>', $result);
    }
}
