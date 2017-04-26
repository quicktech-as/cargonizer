<?php 

namespace Quicktech\Cargonizer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This file is part of Quicktech\Cargonizer package,
 * a wrapper solution for Laravel to consume Cargonizer API.
 *
 * @license MIT
 * @package Quicktech\Cargonizer
 */
class Cargonizer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cargonizer';
    }
}