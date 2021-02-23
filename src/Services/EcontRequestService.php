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

    public function econtRequest($method, $params = array(), $timeout = 10) {
        //production endpoint
        //$endpoint = 'https://ee.econt.com/services';

        //testing endpoint
        $endpoint = 'https://demo.econt.com/ee/services';

        // this is an example only, replace with proper credentials
        $auth = array(
            'login' => 'iasp-dev',
            'password' => 'iasp-dev',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint . '/' . rtrim($method,'/'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        if(!empty($auth)) curl_setopt($ch, CURLOPT_USERPWD, $auth['login'].':'.$auth['password']);
        if(!empty($params)) curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params));
        curl_setopt($ch, CURLOPT_TIMEOUT, !empty($timeout) && intval($timeout) ? $timeout : 4);
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        $jsonResponse = json_decode($response,true);

        return $jsonResponse;
    }

    public function fetchGitHubInformation(): array {
        $response = $this->client->request(
            'GET',
            'https://demo.econt.com/ee/services', [
                'headers' => [
                    'Accept' => 'application/json',
                ]
        ]);

        dump($statusCode = $response->getStatusCode());
        // $statusCode = 200
        dump($contentType = $response->getHeaders()['content-type'][0]);
        // $contentType = 'application/json'
        dump($content = $response->getContent());
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }

    private static function flattenError($err) {
        $msg = trim($err['message']);
        $innerMsgs = array();
        foreach ($err['innerErrors'] as $e) $innerMsgs[] = self::flattenError($e);
        if (!empty($msg) && !empty($innerMsgs)) {
            $msg .= ": ";
        }
        return $msg . implode("; ", array_filter($innerMsgs));
    }
}