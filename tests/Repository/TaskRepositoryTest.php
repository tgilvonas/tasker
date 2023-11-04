<?php

namespace App\Tests\Repository;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskRepositoryTest extends WebTestCase
{
    public function testGetTasksListFunction(): void
    {
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $this->assertIsArray($taskRepository->getTasksList());

        $searchParams = [
            'word' => 'Task 1',
            'date_from' => '2023-10-02',
            'date_to' => '2023-10-27',
            'project_id' => 1,
            'completed' => 1,
        ];

        $this->assertIsArray($taskRepository->getTasksList($searchParams));
    }
}
