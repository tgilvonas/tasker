<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;

class TaskControllerTest extends GenericControllerTestCase
{
    protected object $taskRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = static::getContainer()->get(TaskRepository::class);
    }

    public function testIndexAction(): void
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->loginUser($this->superAdminUser);
        $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction(): void
    {
        $this->client->request('GET', '/tasks/create');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->followRedirects();

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('task_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['task_form[name]'] = 'Task 1';
        $form['task_form[description]'] = 'This is the description of Task 1.';
        $form['task_form[created_at]'] = date('Y-m-d');
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString($this->translate('record_created'), $crawler->text());
    }

    public function testUpdateAction(): void
    {
        $this->client->followRedirects();

        $project = $this->taskRepository->findAll()[0];

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/tasks/update/' . $project->getId());
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('task_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['task_form[name]'] = 'Task 2';
        $form['task_form[description]'] = 'This is the description of Task 2.';
        $form['task_form[created_at]'] = date('Y-m-d');
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString($this->translate('record_updated'), $crawler->text());

        $crawler = $this->client->request('GET', '/tasks/update/' . $project->getId());
        $this->assertStringContainsString('Task 2', $crawler->text());
        $this->assertStringContainsString('This is the description of Task 2.', $crawler->text());
    }

    public function testDeleteAction(): void
    {
        $task = $this->taskRepository->findAll()[0];

        $this->client->followRedirects();

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('POST', '/tasks/delete/' . $task->getId());
        $this->assertStringContainsString($this->translate('record_deleted'), $crawler->text());
    }
}
