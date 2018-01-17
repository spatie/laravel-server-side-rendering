<?php

namespace Spatie\Ssr\Resolvers;

use Spatie\Ssr\Resolver;

class MixResolver implements Resolver
{
    public function getClientScriptUrl(string $identifier) : string
    {
        return mix(
            str_replace_last('.js', '-client.js', $identifier)
        );
    }

    public function getServerScriptContents(string $identifier) : string
    {
        $path = public_path(
            str_replace_last('.js', '-server.js', $identifier)
        );

        return file_get_contents($path);
    }
}
