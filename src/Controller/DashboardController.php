<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

        $projectsCompletionData = $this->projectRepository->getProjectsListQueryBuilder($paramsCountTotals)->getQuery()->getResult();

        return $this->render('dashboard/index.html.twig', [
            'projectsCompletionData' => $projectsCompletionData,
            'tasksTotals' => $this->taskRepository->getTasksTotals(),
        ]);
    }
}
