<?php

/*
 *
 * (c) Muhideen Mujeeb Adeoye <mujeeb.muhideen@gmail.com>
 *
 */

namespace Mujhtech\SendChamp\Facades;

use Illuminate\Support\Facades\Facade;

class SendChamp extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-sendchamp';
    }
}
