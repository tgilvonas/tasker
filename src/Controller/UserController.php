<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/users', name: 'users_index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->getUsersList(),
        ]);
    }

    #[Route('/users/create', name: 'create_user')]
    public function create()
    {
        return $this->render('user/create.html.twig', [

        ]);
    }

    #[Route('/users/update/{id}', name: 'update_user')]
    public function update(int $id)
    {
        return $this->render('user/update.html.twig', [

        ]);
    }

    #[Route('/users/delete/{id}', name: 'delete_user')]
    public function delete(int $id)
    {
        $user = $this->userRepository->find($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('users_index');
    }
}
