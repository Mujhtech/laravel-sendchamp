# laravel-sendchamp

> A Laravel Package for sendchamp api

<p align="center">
    <a href="https://packagist.org/packages/mujhtech/sendchamp"><img src="http://poser.pugx.org/mujhtech/sendchamp/v" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/mujhtech/sendchamp"><img src="http://poser.pugx.org/mujhtech/sendchamp/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://scrutinizer-ci.com/g/Mujhtech/laravel-sendchamp/build-status/master"><img src="https://scrutinizer-ci.com/g/Mujhtech/laravel-sendchamp/badges/build.png?b=master" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/Mujhtech/laravel-sendchamp/?branch=master"><img src="https://scrutinizer-ci.com/g/Mujhtech/laravel-sendchamp/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
    <a href="https://scrutinizer-ci.com/g/Mujhtech/laravel-sendchamp/?branch=master"><img src="https://scrutinizer-ci.com/g/Mujhtech/laravel-sendchamp/badges/coverage.png?b=master" alt="Code Coverage"></a>
    <a href="https://packagist.org/packages/mujhtech/sendchamp"><img src="http://poser.pugx.org/mujhtech/sendchamp/downloads" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/mujhtech/sendchamp"><img src="http://poser.pugx.org/mujhtech/sendchamp/license" alt="License"></a>
</p>

## Installation
 
To get the latest version of Sendchamp, simply require it

```bash
composer require mujhtech/sendchamp
```

Or add the following line to the require block of your `composer.json` file.

```
"mujhtech/sendchamp": "1.0.*"
```

Once Laravel Sendchamp is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

```php
'providers' => [
 ...
 Mujhtech\SendChamp\SendChampServiceProvider::class,
 ...
]
```

> If you use **Laravel >= 5.5** you can skip this step and go to [**`configuration`**](https://github.com/mujhtech/laravel-sendchamp#configuration)

- `SendChamp\SendChamp\SendChampServiceProvider::class`

Also, register the Facade like so:

```php
'aliases' => [
 ...
 'SendChamp' => Mujhtech\SendChamp\Facades\SendChamp::class,
 ...
]
```

## Configuration

You can publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="Mujhtech\SendChamp\SendChampServiceProvider"
```

A configuration-file named `sendchamp.php` with some sensible defaults will be placed in your `config` directory:

```php
<?php

return [

    /**
     * Mode
     * live or test
     *
     */
    'mode' => 'live', // test mode has been removed, the user must buy testing credit on the platform

    /**
     * Public Key
     *
     */
    'publicKey' => getenv('SENDCHAMP_PUBLIC_KEY'),

];
```

## Lumen Configuration
- Open your bootstrap/app.php
- Register your service provider 
- Add your Facade
- Register the config

```php
$app->register(Mujhtech\SendChamp\SendChampServiceProvider::class);
$app->withFacades(true, [
    SendChamp::class => 'SendChamp',
]);
$app->configure('sendchamp');
```
    
## Usage

Open your .env file and add your api key like so:

```php
SENDCHAMP_PUBLIC_KEY=sendchamp_xxxxxxxxxxx
```

_If you are using a hosting service like heroku, ensure to add the above details to your configuration variables._

## Use Case

```php
/**
 * Get wallet report
 * @return array
 */
SendChamp::getWalletReport()

/**
 * Alternatively, use the helper.
 */
sendchamp()->getWalletReport();


/**
* Create or update contact
* @param string $lastname
* @param string $firstname
* @param string $phone
* @param string $email
* @param string $reference
* @return array
*/
SendChamp::createOrUpdateContact($lastname, $firstname, $phone, $email, $reference)

/**
 * Alternatively, use the helper.
 */
sendchamp()->createOrUpdateContact($lastname, $firstname, $phone, $email, $reference);


/**
 * Delete contact
 * @param string $id 
 * @return array
*/
SendChamp::deleteContact($id)

/**
 * Alternatively, use the helper.
 */
sendchamp()->deleteContact($id);


/**
 * * Create sms sender
 * * @param string $send_name
 * * @param string $use_case
 * * You should pass either of the following: Transactional, Marketing, or Transactional & Marketing
 * * @param string $sample_message
 * * @return array
*/
SendChamp::createSmsSender($sender_name, $use_case, $sample_message)

/**
 * Alternatively, use the helper.
 */
sendchamp()->createSmsSender($sender_name, $use_case, $sample_message);


/**
     * Send sms
     * @param string $message
     * @param string $sender_name
     * Represents the sender of the message.
     * This Sender ID must have been requested
     * via the dashboard or use "Sendchamp" as default
     * @param array $numbers
     * This represents the destination phone number.
     * The phone number(s) must be in the international format
     * (Example: 23490126727). You can also send to multiple numbers.
     * To do that put numbers in an array
     * (Example: [ '234somenumber', '234anothenumber' ]).
     * @param string $route e.g ['non_dnd', 'dnd', 'international']
     * @return array
     * 
    */
SendChamp::sendSms($message, $sender_name, $numbers, $route)

/**
 * Alternatively, use the helper.
 */
sendchamp()->sendSms($message, $sender_name, $numbers, $route);


/**
     * Get sms status
     * @param string $sms_id 
     * ID of the SMS that was sent
     * @return array
    */
SendChamp::fetchSmsStatus($sms_id)

/**
 * Alternatively, use the helper.
 */
sendchamp()->fetchSmsStatus($sms_id);



/**
     * Send voice
     * @param string $message
     * @param string $sender_name
     * Represents the sender of the message.
     * This Sender ID must have been requested via
     * the dashboard or use "Sendchamp" as default
     * @param string $number
     * The number represents the destination phone number.
     * The number must be in international format (E.g. 2348012345678)
     * @return array
    */
SendChamp::sendVoice($message,  $sender_name,  $number)

/**
 * Alternatively, use the helper.
 */
sendchamp()->sendVoice($message, $sender_name, $number);


/**
     * Send whatsapp otp
     * @param string $template_code
     * You can find this on the template page under Whatsapp Channel of your Sendchamp dashboard
     * @param string $sender_number
     * Your approved Whatsapp number on Sendchamp.
     * You can use our phone number if you have not registered a number 2347067959173
     * @param string $recipient
     * Whatsapp number of the customer you are sending the message to
     * @param string $message
     * @return array
    */
SendChamp::sendWhatsappOtp($template_code, $message, $sender_number, $recipient)

/**
 * Alternatively, use the helper.
 */
sendchamp()->sendWhatsappOtp($template_code, $message, $sender_number, $recipient);



/**
     * Send otp message
     * @param string $channel
     * @param string $token_type // numeric or alphanumeric
     * @param int $token_length 
     * The length of the token you want to send to your customer. Minimum is 5
     * @param int $expiry_day
     * How long you want to the to be active for in minutes. (E.g. 10 means 10 minutes )
     * @param string $customer_email 
     * @param string $customer_mobile_number 
     * @param array $meta_data can be empty but you need to pass array like ['data' => []]
     * @param string $sender
     * Specify the sender you want to use. This is important
     * when using SMS OR Whatsapp Channel or we will select a
     * default sender from your account. Eg: KUDA OR +234810000000
     * @return array
    */
SendChamp::sendOtp($channel, $token_type, $token_length, $expiry_day, $customer_email $customer_mobile_number, $meta_data, $sender)

/**
 * Alternatively, use the helper.
 */
sendchamp()->sendOtp($channel, $token_type, $token_length, $expiry_day, $customer_email $customer_mobile_number, $meta_data, $sender);



/**
     * Confirm otp
     * @param string $reference
     * The unique reference that was returned as response when the OTP was created
     * @param string $otp
     * The OTP that was sent to the customer.
     * @return array
    */
SendChamp::confirmOtp($reference, $otp)

/**
 * Alternatively, use the helper.
 */
sendchamp()->confirmOtp($reference, $otp);

```

## Code Quality
- Run `vendor/bin/pint`

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
