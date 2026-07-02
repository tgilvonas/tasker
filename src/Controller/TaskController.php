<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected ProjectRepository $projectRepository;

    protected TaskRepository $taskRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProjectRepository $projectRepository,
        TaskRepository $taskRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
        $this->taskRepository = $taskRepository;
    }

    #[Route('/tasks', name: 'tasks_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $searchParams = $request->query->all();
        $tasksQuery = $this->taskRepository->getTasksList($searchParams);

        return $this->render('task/index.html.twig', [
            'tasks' => $paginator->paginate($tasksQuery, $request->query->getInt('page', 1), 10),
            'projects' => $this->projectRepository->getProjectsListQueryBuilder()->getQuery()->getResult(),
            'searchParams' => $searchParams,
        ]);
    }

    #[Route('/tasks/create', name: 'create_task')]
    public function create(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskFormType::class, $task, ['translation_domain' => 'app']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_created');
            return $this->redirectToRoute('tasks_index');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tasks/update/{id}', name: 'update_task')]
    public function update(Request $request, int $id): Response
    {
        $task = $this->taskRepository->find($id);

        $form = $this->createForm(TaskFormType::class, $task, ['translation_domain' => 'app']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_updated');
            return $this->redirectToRoute('tasks_index');
        }

        return $this->render('task/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tasks/delete/{id}', name: 'delete_task')]
    public function delete(int $id): Response
    {
        $task = $this->taskRepository->find($id);

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->addFlash('success', 'record_deleted');

        return $this->redirectToRoute('tasks_index');
    }
}
