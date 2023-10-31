<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(Request $request): Response
    {
        $searchParams = $request->query->all();

        return $this->render('task/index.html.twig', [
            'tasks' => $this->taskRepository->getTasksList($searchParams),
            'projects' => $this->projectRepository->getProjectsList(),
            'searchParams' => $searchParams,
        ]);
    }

    #[Route('/tasks/create', name: 'create_task')]
    public function create(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskFormType::class, $task);

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

        $form = $this->createForm(TaskFormType::class, $task);

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
