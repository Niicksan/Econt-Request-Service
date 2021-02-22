<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(): Response
    {
        $tasks = $this
            ->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        return $this->render('index/index.html.twig',
            ['tasks' => $tasks]
        );
    }
}
