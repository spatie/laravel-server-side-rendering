<?php

namespace Spatie\Ssr\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\Ssr\Renderer
 */
class Ssr extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ssr';
    }
}
