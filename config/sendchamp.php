<?php
/*
 *
 * (c) Muhideen Mujeeb Adeoye <mujeeb.muhideen@gmail.com>
 *
 */

return [

    /**
     * Mode
     * live or test
     */
    'mode' => 'live',

    /**
     * Live API url
     */
    'baseUrl' => 'https://api.sendchamp.com/api/v1',

    /**
     * Test Api Url
     */
    'sandboxUrl' => 'https://sandbox-api.sendchamp.com/api/v1',

    /**
     * Public Key
     */
    'publicKey' => getenv('SENDCHAMP_PUBLIC_KEY'),

    /**
     * Webhook
     */
    'webhook' => getenv('SENDCHAMP_WEBHOOK'),

];
