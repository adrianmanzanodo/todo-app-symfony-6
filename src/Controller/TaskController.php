<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig' , [
            'message' => 'Welcome to index controller!',
            'tasks' => []
        ]);
    }

    #[Route('/task/create', name:'app_create')]
    public function create(): Response
    {
        return $this->render('task/create.html.twig' , [
            'message' => 'Welcome to your new controller!'
        ]);
    }
}