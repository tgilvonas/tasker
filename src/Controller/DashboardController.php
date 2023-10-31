<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    protected ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
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
        ]);
    }
}
