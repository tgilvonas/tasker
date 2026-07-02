<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected ProjectRepository $projectRepository;

    public function __construct(EntityManagerInterface $entityManager, ProjectRepository $projectRepository)
    {
        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
    }

    #[Route('/projects', name: 'projects_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $paramsCountTotals = [
            'total' => true,
            'completed' => true,
            'uncompleted' => true,
        ];
        $projectsQuery = $this->projectRepository->getProjectsListQueryBuilder($paramsCountTotals);

        return $this->render('project/index.html.twig', [
            'projects' => $paginator->paginate($projectsQuery, $request->query->getInt('page', 1), 10),
        ]);
    }

    #[Route('/projects/create', name: 'create_project')]
    public function create(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectFormType::class, $project, ['translation_domain' => 'app']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_created');
            return $this->redirectToRoute('projects_index');
        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projects/update/{id}', name: 'update_project')]
    public function update(Request $request, int $id): Response
    {
        $project = $this->projectRepository->find($id);

        $form = $this->createForm(ProjectFormType::class, $project, ['translation_domain' => 'app']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_updated');
            return $this->redirectToRoute('projects_index');
        }

        return $this->render('project/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projects/delete/{id}', name: 'delete_project')]
    public function delete(int $id): Response
    {
        $project = $this->projectRepository->find($id);

        $this->entityManager->remove($project);
        $this->entityManager->flush();

        $this->addFlash('success', 'record_deleted');

        return $this->redirectToRoute('projects_index');
    }
}
