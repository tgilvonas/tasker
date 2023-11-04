<?php

namespace App\Tests\Repository;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectRepositoryTest extends WebTestCase
{
    public function testGetProjectsListFunction(): void
    {
        $projectRepository = static::getContainer()->get(ProjectRepository::class);

        $this->assertIsArray($projectRepository->getProjectsList());

        $params = [
            'total' => true,
            'completed' => true,
            'uncompleted' => true,
        ];
        $this->assertIsArray($projectRepository->getProjectsList($params));

        $params = [
            'total' => true,
            'completed' => true,
        ];
        $this->assertIsArray($projectRepository->getProjectsList($params));
    }
}
