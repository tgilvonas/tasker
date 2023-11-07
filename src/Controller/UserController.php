<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\UserProfileFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
            'users' => $paginator->paginate($usersQuery, $request->query->getInt('page', 1), 10),
        ]);
    }

    #[Route('/users/create', name: 'create_user')]
    public function create(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user, ['translation_domain' => 'app']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
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

        $form = $this->createForm(UserFormType::class, $user, ['translation_domain' => 'app']);

        $oldPassword = $user->getPassword();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if (empty($user->getPassword())) {
                $user->setPassword($oldPassword);
            } else {
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
            }
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

    #[Route('/profile', name: 'user_profile')]
    public function profile(Request $request, Security $security): Response
    {
        $user = $security->getUser();

        $oldPassword = $user->getPassword();
        $roles = $user->getRoles();

        $form = $this->createForm(UserProfileFormType::class, $user, ['translation_domain' => 'app']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if (empty($user->getPassword())) {
                $user->setPassword($oldPassword);
            } else {
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
            }

            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'profile_updated');
            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
