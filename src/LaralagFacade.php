<?php

namespace Abianbiya\Laralag;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Abianbiya\Laralag\Skeleton\SkeletonClass
 */
class LaralagFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laralag';
    }
}
