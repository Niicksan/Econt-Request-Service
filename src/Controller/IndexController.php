<?php

namespace App\Controller;

use App\Entity\Task;
use App\Services\EcontRequestService;
use http\Client;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(EcontRequestService $requestService): Response
    {
        dump($data = $this->getCities("Nomenclatures/NomenclaturesService.getCities.json", array('countryCode' => 'BGR')));
        dump($data['cities'][5319]);

        $tasks = $this
            ->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        return $this->render('index/index.html.twig',
            ['tasks' => $tasks]
        );
    }

    public static function getCities($method, $params = array(), $timeout = 10) {
        $client = new NativeHttpClient();

        //production endpoint
        //$endpoint = 'https://ee.econt.com/services';

        //testing endpoint
        $url = 'https://demo.econt.com/ee/services'. '/' . rtrim($method,'/');

        $response = $client->request(
            'POST', $url,  [
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

        ($content = $response->toArray());

        // cancels the request/response
        $response->cancel();

        return $content;
    }
}
