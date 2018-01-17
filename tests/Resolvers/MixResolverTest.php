<?php

namespace Spatie\Ssr\Tests\Resolvers;

use Spatie\Ssr\Tests\TestCase;
use Spatie\Ssr\Resolvers\MixResolver;

class MixResolverTest extends TestCase
{
    /** @var string */
    private $manifestDirectory;

    public function setUp()
    {
        parent::setUp();

        $this->manifestDirectory = __DIR__ . '/../public';
    }

    /** @test */
    public function it_can_resolve_a_client_scripts_url()
    {
        $resolver = new MixResolver($this->manifestDirectory);

        $clientScriptUrl = $resolver->getClientScriptUrl('js/app.js');

        $this->assertEquals('/js/app-client.js?id=12345', $clientScriptUrl);
    }

    /** @test */
    public function it_can_resolve_a_server_scripts_contents()
    {
        $resolver = new MixResolver($this->manifestDirectory);

        $serverScript = $resolver->getServerScriptContents('js/app.js');

        $this->assertEquals("dispatch('<p>Hello, world!</p>');\n", $serverScript);
    }
}
