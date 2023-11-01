<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    protected ProjectRepository $projectRepository;

    protected TaskRepository $taskRepository;

    public function __construct(ProjectRepository $projectRepository, TaskRepository $taskRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->taskRepository = $taskRepository;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $paramsCountTotals = [
            'total' => true,
            'completed' => true,
        ];

        return $this->render('dashboard/index.html.twig', [
            'projectsCompletionData' => $this->projectRepository->getProjectsList($paramsCountTotals),
            'tasksTotals' => $this->taskRepository->getTasksTotals(),
        ]);
    }
}
