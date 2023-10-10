<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    protected ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    #[Route('/projects', name: 'projects_index')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $this->projectRepository->getProjectsList(),
        ]);
    }

    #[Route('/projects/create', name: 'create_project')]
    public function create()
    {
        return $this->render('project/create.html.twig', [

        ]);
    }

    #[Route('/projects/update/{id}', name: 'update_project')]
    public function update(int $id)
    {
        return $this->render('project/update.html.twig', [

        ]);
    }

    #[Route('/projects/delete/{id}', name: 'delete_project')]
    public function delete(int $id)
    {
        dd('project delete');
    }
}
