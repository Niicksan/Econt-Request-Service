<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/create", name="task_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('task/create.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/task/edit", name="task_edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $result = $this->create();

        return $this->render('task/edit.html.twig'
        );
    }
}