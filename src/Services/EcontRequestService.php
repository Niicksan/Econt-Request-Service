<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EcontRequestService
{
    private $logger;
    private $client;

    public function __construct(LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->logger = $logger;
        $this->client = $client;
    }

//    public function econtRequest($method, $params = array(), $timeout = 10) {
//        //production endpoint
//        //$endpoint = 'https://ee.econt.com/services';
//
//        //testing endpoint
//        $endpoint = 'https://demo.econt.com/ee/services';
//
//        // this is an example only, replace with proper credentials
//        $auth = array(
//            'login' => 'iasp-dev',
//            'password' => 'iasp-dev',
//        );
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $endpoint . '/' . rtrim($method,'/'));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
//        if(!empty($auth)) curl_setopt($ch, CURLOPT_USERPWD, $auth['login'].':'.$auth['password']);
//        if(!empty($params)) curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params));
//        curl_setopt($ch, CURLOPT_TIMEOUT, !empty($timeout) && intval($timeout) ? $timeout : 4);
//
//        $response = curl_exec($ch);
//        dump($httpStatus = curl_getinfo($ch,CURLINFO_HTTP_CODE));
//
//        curl_close($ch);
//
//        dump($params);
//        dump(json_encode($params));
//        ($jsonResponse = json_decode($response,true));
//
//        $jsonResponse = json_decode($response,true);
//        if(!$jsonResponse) {
//            throw new \Exception("Invalid response.");
//        }
//        if(strpos($httpStatus,'2') !== 0) {
//            throw new \Exception(self::flattenError($jsonResponse));//simple error handling by combining all the returned error's messages
//        } else {
//            return $jsonResponse;
//        }
//    }

    public function econtRequest($method, $params = array(), $timeout = 20) {
        //production endpoint
        //$endpoint = 'https://ee.econt.com/services';

        //testing endpoint
        $endpoint = 'https://demo.econt.com/ee/services'. '/' . rtrim($method,'/');

        $response = $this->client->request(
            'POST', $endpoint,  [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'auth_basic' => ['iasp-dev', 'iasp-dev'],
                'verify_peer' => false,
                'verify_host' => false,
                'json' => $params,
                'timeout' => !empty($timeout) && intval($timeout) ? $timeout : 10,
            ]
        );

        $content = $response->toArray();

        // cancels the request/response
        $response->cancel();

        return $content;
    }

//    private static function flattenError($err) {
//        $msg = trim($err['message']);
//        $innerMsgs = array();
//        foreach ($err['innerErrors'] as $e) $innerMsgs[] = self::flattenError($e);
//        if (!empty($msg) && !empty($innerMsgs)) {
//            $msg .= ": ";
//        }
//        return $msg . implode("; ", array_filter($innerMsgs));
//    }
}