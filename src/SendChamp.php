<?php

/*
 *
 * (c) Muhideen Mujeeb Adeoye <mujeeb.muhideen@gmail.com>
 *
 */

namespace Mujhtech\SendChamp;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class SendChamp {

    protected $pubicKey;


    protected $client;


    protected $response;


    protected $baseUrl;


    public function __construct()
    {
        $this->getKey();
        $this->getBaseUrl();
        $this->setRequestOptions();
    }

    public function getBaseUrl()
    {
        $this->baseUrl = Config::get('sendchamp.mode') == "live" ? Config::get('sendchamp.baseUrl') : Config::get('sendchamp.sandboxUrl');
    }


    public function getKey()
    {
        $this->publicKey = Config::get('sendchamp.mode') == "live" ? Config::get('sendchamp.publicKey') : null;
    }

    private function setRequestOptions()
    {
        $authBearer = 'Bearer '. $this->publicKey;

        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Authorization' => $authBearer,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ]
            ]
        );

        return $this;
    }


    private function setHttpResponse($url, $method, $body = [])
    {
        if (is_null($method)) {
            throw new IsNullException("Empty method not allowed");
        }

        $this->response = $this->client->{strtolower($method)}(
            $this->baseUrl . $url,
            ["body" => json_encode($body)]
        );

        return $this;
    }


    private function getResponse()
    {
        return json_decode($this->response->getBody(), true);
    }

    public function createSmsSender(string $sender_name, string $use_case, string $sample_message){

        $data = [
            'sample' => $sample_message,
            'use_case' => $use_case,
            'sender_name' => $sender_name
        ];

        
        return $this->setRequestOptions()->setHttpResponse('/sms/send', 'POST', $data)->getResponse();

    }

    public function sendSms(string $message, string $sender_name, array $numbers){

        $data = [
            'to' => $numbers,
            'message' => $message,
            'sender_name' => $sender_name
        ];

        
        return $this->setRequestOptions()->setHttpResponse('/sms/send', 'POST', $data)->getResponse();

    }

    public function fetchSmsStatus(string $sms_id){

        return $this->setRequestOptions()->setHttpResponse('/sms/'.$sms_id.'/report', 'GET', [])->getResponse();

    }


    public function sendVoice(string $message, string $sender_name, string $numbers){

        $data = [
            'customer_mobile_number' => $numbers,
            'message' => $message,
            'sender_name' => $sender_name
        ];

        
        return $this->setRequestOptions()->setHttpResponse('/voice/send', 'POST', $data)->getResponse();

    }


    public function sendWhatsappOtp(string $template_code, string $message, string $sender_number, string $recipient){

        $data = [
            'recipient' => $recipient,
            'template_code' => $template_code,
            'message' => $message,
            'sender' => $sender_number
        ];

        
        return $this->setRequestOptions()->setHttpResponse('/whatsapp/template/send', 'POST', $data)->getResponse();

    }

    public function sendOtp(string $channel, string $token_type, int $token_length,
     int $expiry_day, string $customer_email, string $customer_mobile_number, array $meta_data, string $sender){

        $data = [
            'channel' => $channel,
            'token_type' => $token_type,
            'token_length' => $token_length,
            'expiration_time' => $expiry_day,
            'customer_email' => $customer_email,
            'customer_mobile_number' => $customer_mobile_number,
            'meta_data' => $meta_data,
            'sender' => $sender
        ];

        
        return $this->setRequestOptions()->setHttpResponse('/verification/create', 'POST', $data)->getResponse();

    }


    public function confirmOtp(string $reference, string $otp){

        $data = [
            'verification_reference' => $reference,
            'verification_otp' => $otp
        ];

        
        return $this->setRequestOptions()->setHttpResponse('/verification/confirm', 'POST', $data)->getResponse();

    }


    public function getContactList(){
        
        return $this->setRequestOptions()->setHttpResponse('/contacts', 'GET', [])->getResponse();

    }


    public function createOrUpdateContact(string $lastname, string $firstname, string $phone, string $email, string $reference){

        $data = [

            'last_name' => $lastname,
            'first_name' => $firstname,
            'phone_number' => $phone,
            'email' => $email,
            'external_user_id' => $reference

        ];
        
        return $this->setRequestOptions()->setHttpResponse('/contacts', 'POST', $data)->getResponse();

    }


    public function deleteContact(string $id){

        
        return $this->setRequestOptions()->setHttpResponse('/contact/'.$id.'/delete', 'POST', [])->getResponse();

    }



    /**
     * Get wallet report
     *
     * @return array
     */

    public function getWalletReport(){
        
        return $this->setRequestOptions()->setHttpResponse('/report/wallet/list', 'GET', [])->getResponse();

    }

}