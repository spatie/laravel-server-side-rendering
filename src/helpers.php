<?php

function ssr(string $entry = null)
{
    if (func_num_args() === 0) {
        return app('ssr');
    }

    return app('ssr')->entry($entry);
}
