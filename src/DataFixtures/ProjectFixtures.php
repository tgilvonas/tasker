<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(UserRepository::class);

        for ($i = 1; $i <= 2; $i++) {
            $project = new Project();
            $project->setName('Project ' . $i);
            $project->setDescription('This is description of Project ' . $i . '. Lorem ipsum dolor sit amet');
            $project->setOrd($i);

            $users = $userRepository->findAll();
            unset($users[$i]);

            $project->setUsers($users);

            $manager->persist($project);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
