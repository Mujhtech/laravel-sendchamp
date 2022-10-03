<?php

/*
 *
 * (c) Muhideen Mujeeb Adeoye <mujeeb.muhideen@gmail.com>
 *
 */

namespace Mujhtech\SendChamp;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Mujhtech\SendChamp\Exceptions\SendChampException;

class SendChamp
{
    /**
     * @var array
     */
    private $useCase = ['Transactional', 'Marketing', 'Transactional & Marketing'];

    /**
     * @var array
     */
    private $channel = ['voice', 'sms', 'whatsapp', 'email'];

    /**
     * @var array
     */
    private $tokenType = ['numeric', 'alphanumeric'];

    /**
     * @var array
     */
    private $smsRoute = ['non_dnd', 'dnd', 'international', 'PREMIUM_NG'];

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $client;

    /**
     * Response from sendchamp api
     *
     * @var mixed
     */
    protected $response;

    /**
     * @var string
     */
    protected $baseUrl;

    public function __construct()
    {
        $this->getKey();
        $this->getBaseUrl();
        $this->setRequestOptions();
    }

    /**
     * Get base url from sendchamp config
     */
    public function getBaseUrl()
    {
        $this->baseUrl = Config::get('sendchamp.mode') == 'live' ? Config::get('sendchamp.baseUrl') : Config::get('sendchamp.sandboxUrl');
    }

    /**
     * Get public key from sendchamp cofig
     */
    public function getKey()
    {
        $this->publicKey = Config::get('sendchamp.publicKey');
    }

    /**
     * Set request options
     *
     * @return SendChamp
     */
    private function setRequestOptions()
    {
        $authBearer = 'Bearer '.$this->publicKey;

        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Authorization' => $authBearer,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]
        );

        return $this;
    }

    /**
     * Set http response
     *
     * @param  string  $url
     * @param  null  $method
     * @param  array  $body
     * @return SendChamp
     *
     * @throws SendChampException
     */
    private function setHttpResponse(string $url, $method = null, array $body = [])
    {
        if (is_null($method)) {
            throw new SendChampException('Empty method not allowed');
        }

        $this->response = $this->client->{strtolower($method)}(
            $this->baseUrl.$url,
            ['body' => json_encode($body)]
        );

        return $this;
    }

    /**
     * Decode json response into an array
     *
     * @return array
     */
    private function getResponse()
    {
        return json_decode($this->response->getBody(), true);
    }

    /**
     * Create sms sender
     *
     * @param  string  $send_name
     * @param  string  $use_case
     * You should pass either of the following: Transactional, Marketing, or Transactional & Marketing
     * @param  string  $sample_message
     * @return array
     */
    public function createSmsSender(string $sender_name, string $use_case,
        string $sample_message)
    {
        if (! in_array($use_case, $this->useCase)) {
            throw new SendChampException('Invalid Use case');
        }

        $data = [
            'sample' => $sample_message,
            'use_case' => $use_case,
            'sender_name' => $sender_name,
        ];

        return $this->setRequestOptions()->setHttpResponse('/sms/sender/create', 'POST', $data)->getResponse();
    }

    /**
     * Send sms
     *
     * @param  string  $message
     * @param  string  $sender_name
     * Represents the sender of the message.
     * This Sender ID must have been requested
     * via the dashboard or use "Sendchamp" as default
     * @param  array  $numbers
     * This represents the destination phone number.
     * The phone number(s) must be in the international format
     * (Example: 23490126727). You can also send to multiple numbers.
     * To do that put numbers in an array
     * (Example: [ '234somenumber', '234anothenumber' ]).
     * @param  string  $route e.g ['non_dnd', 'dnd', 'international']
     * @return array
     */
    public function sendSms(string $message, string $sender_name, array $numbers, string $route = '')
    {
        if (! empty($route) && ! in_array($route, $this->smsRoute)) {
            throw new SendChampException('Invalid sms route');
        }

        $data = [
            'to' => $numbers,
            'message' => $message,
            'sender_name' => $sender_name,
            'route' => $route,
        ];

        return $this->setRequestOptions()->setHttpResponse('/sms/send', 'POST', $data)->getResponse();
    }

    /**
     * Get sms status
     *
     * @param  string  $sms_id
     * ID of the SMS that was sent
     * @return array
     */
    public function fetchSmsStatus(string $sms_id)
    {
        return $this->setRequestOptions()->setHttpResponse('/sms/'.$sms_id.'/report', 'GET', [])->getResponse();
    }

    /**
     * Send voice
     *
     * @param  string  $message
     * @param  string  $sender_name
     * Represents the sender of the message.
     * This Sender ID must have been requested via
     * the dashboard or use "Sendchamp" as default
     * @param  string  $number
     * The number represents the destination phone number.
     * The number must be in international format (E.g. 2348012345678)
     * @return array
     */
    public function sendVoice(string $message, string $sender_name, string $number)
    {
        $data = [
            'customer_mobile_number' => $number,
            'message' => $message,
            'sender_name' => $sender_name,
        ];

        return $this->setRequestOptions()->setHttpResponse('/voice/send', 'POST', $data)->getResponse();
    }

    /**
     * Send whatsapp otp
     *
     * @param  string  $template_code
     * You can find this on the template page under Whatsapp Channel of your Sendchamp dashboard
     * @param  string  $sender_number
     * Your approved Whatsapp number on Sendchamp.
     * You can use our phone number if you have not registered a number 2347067959173
     * @param  string  $recipient
     * Whatsapp number of the customer you are sending the message to
     * @param  string  $message
     * @return array
     */
    public function sendWhatsappOtp(string $template_code, string $message,
        string $sender_number, string $recipient)
    {
        $data = [
            'recipient' => $recipient,
            'template_code' => $template_code,
            'message' => $message,
            'sender' => $sender_number,
        ];

        return $this->setRequestOptions()->setHttpResponse('/whatsapp/template/send', 'POST', $data)->getResponse();
    }

    /**
     * Send otp message
     *
     * @param  string  $channel
     * @param  string  $token_type
     * @param  int  $token_length
     * The length of the token you want to send to your customer. Minimum is 4
     * @param  int  $expiry_day
     * How long you want to the to be active for in minutes. (E.g 10 means 10 minutes )
     * @param  string  $customer_email
     * @param  string  $customer_mobile_number
     * @param  array  $meta_data
     * @param  string  $sender
     * Specify the sender you want to use. This is important
     * when using SMS OR Whatsapp Channel or we will select a
     * default sender from your account. Eg: KUDA OR +234810000000
     * @return array
     */
    public function sendOtp(string $channel, string $token_type, int $token_length,
       int $expiry_day, string $customer_email, string $customer_mobile_number, array $meta_data, string $sender)
    {
        if (! in_array($token_type, $this->tokenType)) {
            throw new SendChampException('Invalid token type');
        }

        if (! in_array($channel, $this->channel)) {
            throw new SendChampException('Invalid channel');
        }

        $data = [
            'channel' => $channel,
            'token_type' => $token_type,
            'token_length' => $token_length,
            'expiration_time' => $expiry_day,
            'customer_email' => $customer_email,
            'customer_mobile_number' => $customer_mobile_number,
            'meta_data' => $meta_data,
            'sender' => $sender,
        ];

        return $this->setRequestOptions()->setHttpResponse('/verification/create', 'POST', $data)->getResponse();
    }

    /**
     * Confirm otp
     *
     * @param  string  $reference
     * The unique reference that was returned as response when the OTP was created
     * @param  string  $otp
     * The OTP that was sent to the customer.
     * @return array
     */
    public function confirmOtp(string $reference, string $otp)
    {
        $data = [
            'verification_reference' => $reference,
            'verification_code' => $otp,
        ];

        return $this->setRequestOptions()->setHttpResponse('/verification/confirm', 'POST', $data)->getResponse();
    }

    /**
     * Get contacts list
     *
     * @return array
     */
    public function getContactList()
    {
        return $this->setRequestOptions()->setHttpResponse('/contacts', 'GET', [])->getResponse();
    }

    /**
     * Create or update contact
     *
     * @param  string  $lastname
     * @param  string  $firstname
     * @param  string  $phone
     * @param  string  $email
     * @param  string  $reference
     * @return array
     */
    public function createOrUpdateContact(string $lastname, string $firstname, string $phone, string $email, string $reference)
    {
        $data = [

            'last_name' => $lastname,
            'first_name' => $firstname,
            'phone_number' => $phone,
            'email' => $email,
            'external_user_id' => $reference,

        ];

        return $this->setRequestOptions()->setHttpResponse('/contacts', 'POST', $data)->getResponse();
    }

    /**
     * Delete contact
     *
     * @param  string  $id
     * @return array
     */
    public function deleteContact(string $id)
    {
        return $this->setRequestOptions()->setHttpResponse('/contact/'.$id.'/delete', 'POST', [])->getResponse();
    }

    /**
     * Get wallet report
     *
     * @return array
     */
    public function getWalletReport()
    {
        return $this->setRequestOptions()->setHttpResponse('/report/wallet/list', 'GET', [])->getResponse();
    }
}
