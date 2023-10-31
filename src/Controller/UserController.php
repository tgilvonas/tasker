<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected UserRepository $userRepository;

    protected UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/users', name: 'users_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $usersQuery = $this->userRepository->getUsersIndexQuery();

        return $this->render('user/index.html.twig', [
            'users' => $paginator->paginate($usersQuery, $request->query->getInt('page', 1), 2),
        ]);
    }

    #[Route('/users/create', name: 'create_user')]
    public function create(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_created');
            return $this->redirectToRoute('users_index');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/update/{id}', name: 'update_user')]
    public function update(Request $request, int $id): Response
    {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'record_updated');
            return $this->redirectToRoute('users_index');
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/delete/{id}', name: 'delete_user')]
    public function delete(int $id): Response
    {
        $user = $this->userRepository->find($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'record_deleted');

        return $this->redirectToRoute('users_index');
    }
}
