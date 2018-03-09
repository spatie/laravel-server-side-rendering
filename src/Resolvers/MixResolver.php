<?php

namespace Spatie\Ssr\Resolvers;

class MixResolver
{
    /** @var bool */
    protected $enabled;

    public function __construct(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    public function __invoke(string $identifier) : string
    {
        if (! $this->enabled) {
            return $identifier;
        }

        list($publicPathWithoutQuery) = explode('?', $identifier);

        return public_path($publicPathWithoutQuery);
    }
}
