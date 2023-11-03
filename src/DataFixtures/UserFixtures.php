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
        $user = new User();
        $user->setEmail('petras@tasker.com');
        $user->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_USER']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'pa55word123'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('jonas@tasker.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'pa55word123'));
        $manager->persist($user);

        $manager->flush();
    }
}
