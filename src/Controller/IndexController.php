<?php

namespace App\Controller;

use App\Entity\Task;
use App\Services\EcontRequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(EcontRequestService $requestService): Response
    {
        dump($adad = $requestService->fetchGitHubInformation());
        $tasks = $this
            ->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        return $this->render('index/index.html.twig',
            ['tasks' => $tasks]
        );
    }
}
