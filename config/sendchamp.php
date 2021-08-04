<?php
/*
 *
 * (c) Muhideen Mujeeb Adeoye <mujeeb.muhideen@gmail.com>
 *
 */

return [

    /**
     * Mode 
     *
     */
    'mode' => 'test',


    /**
     * Live API url
     *
     */
    'baseUrl' => 'https://api.sendchamp.com/api/v1',

    /**
     * Test Api Url
     *
     */
    'sandboxUrl' => 'https://sandbox-api.sendchamp.com/api/v1',

    /**
     * Public Key
     *
     */
    'publicKey' => getenv('SENDCHAMP_PUBLIC_KEY'),

    /**
     * Secret Key
     *
     */
    'secretKey' => getenv('SENDCHAMP_SECRET_KEY'),


    /**
     * Webhook
     *
     */
    'webhook' => getenv('SENDCHAMP_WEBHOOK'),


];

