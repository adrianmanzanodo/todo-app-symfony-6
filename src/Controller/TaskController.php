<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\TaskType;
use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'message' => 'Welcome to index controller!',
            'tasks' => [],
        ]);
    }

    #[Route('/task/create', name:'task_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index');
        }

        return $this->renderForm('task/create.html.twig', [
            'form' => $form,
        ]);
    }
}
