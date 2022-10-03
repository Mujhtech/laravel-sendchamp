<?php

/*
 *
 * (c) Muhideen Mujeeb Adeoye <mujeeb.muhideen@gmail.com>
 *
 */

if (! function_exists('sendchamp')) {
    function sendchamp()
    {
        return app()->make('laravel-sendchamp');
    }
}
