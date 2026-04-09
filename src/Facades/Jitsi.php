<?php

namespace VcMeet\Jitsi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VcMeet\Jitsi\JitsiServiceProvider
 */
class Jitsi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jitsi';
    }
}
