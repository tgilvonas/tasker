<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
    public function create(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectFormType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            return $this->redirectToRoute('projects_index');
        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projects/update/{id}', name: 'update_project')]
    public function update(Request $request, int $id): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

        $form = $this->createForm(ProjectFormType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            return $this->redirectToRoute('projects_index');
        }

        return $this->render('project/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projects/delete/{id}', name: 'delete_project')]
    public function delete(int $id): Response
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

        $this->entityManager->remove($project);
        $this->entityManager->flush();

        return $this->redirectToRoute('projects_index');
    }
}
