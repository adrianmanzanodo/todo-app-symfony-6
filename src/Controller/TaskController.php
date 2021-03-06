<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\TaskType;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends AbstractController
{
    private $taskRepository;
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->taskRepository = $this->doctrine->getRepository(Task::class);
    }

    #[Route('/', name: 'task_index')]
    public function index(Request $request): Response
    {
        $taskRepository = $this->doctrine->getRepository(Task::class);
        $tasks = $taskRepository->findActiveTasks();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/task/complete/{id}', name: 'task_complete')]
    public function complete($id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        $this->taskRepository->complete($task);
        return new JsonResponse();
    }

    #[Route('/task/edit/{taskId}', name: 'task_edit')]
    public function edit(Request $request, $taskId = null): Response
    {   
        //Get task if cames from edit, if not create a new Task.
        $task = !isset($taskId)? new Task():  $this->taskRepository->find($taskId); 
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
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
