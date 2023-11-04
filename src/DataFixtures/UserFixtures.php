<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    protected UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userEmails = [
            'peter@tasker.com',
            'john@tasker.com',
            'kate@tasker.com',
        ];

        foreach ($userEmails as $key => $userEmail) {

            $roles = $key == 0 ? ['ROLE_SUPER_ADMIN', 'ROLE_USER'] : ['ROLE_USER'];

            $user = new User();
            $user->setEmail($userEmail);
            $user->setRoles($roles);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'pa55word123'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
