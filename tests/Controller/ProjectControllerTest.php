<?php

namespace App\Tests\Controller;

use App\Repository\ProjectRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProjectControllerTest extends GenericControllerTestCase
{
    protected object $projectRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->projectRepository = static::getContainer()->get(ProjectRepository::class);
    }

    public function testIndexAction(): void
    {
        $this->client->request('GET', '/projects');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/projects');
        $this->assertResponseIsSuccessful();

        $this->client->loginUser($this->superAdminUser);
        $this->client->request('GET', '/projects');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction(): void
    {
        $this->client->request('GET', '/projects/create');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->followRedirects();

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/projects/create');
        $this->assertResponseStatusCodeSame(403);
        $this->client->request('POST', '/projects/create');
        $this->assertResponseStatusCodeSame(403);

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/projects/create');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('project_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['project_form[name]'] = 'Project 1';
        $form['project_form[description]'] = 'This is the description of Project 1.';
        $form['project_form[ord]'] = 1;
        $form['project_form[created_at]'] = date('Y-m-d');
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString('Project 1', $crawler->text());
        $this->assertStringContainsString($this->translate('record_created'), $crawler->text());
    }

    public function testUpdateAction(): void
    {
        $this->client->followRedirects();

        $project = $this->projectRepository->findAll()[0];

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/projects/update/' . $project->getId());
        $this->assertResponseIsSuccessful();

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/projects/update/' . $project->getId());
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('project_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['project_form[name]'] = 'Project 2';
        $form['project_form[description]'] = 'This is the description of Project 2.';
        $form['project_form[ord]'] = 2;
        $form['project_form[created_at]'] = '2023-10-02';
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString($this->translate('record_updated'), $crawler->text());

        $crawler = $this->client->request('GET', '/projects/update/' . $project->getId());
        $this->assertStringContainsString('Project 2', $crawler->text());
        $this->assertStringContainsString('This is the description of Project 2.', $crawler->text());
    }

    public function testDeleteAction(): void
    {
        $project = $this->projectRepository->findAll()[0];

        $this->client->followRedirects();

        $this->client->loginUser($this->user);
        $this->client->request('POST', '/projects/delete/' . $project->getId());
        $this->assertResponseStatusCodeSame(403);

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('POST', '/projects/delete/' . $project->getId());
        $this->assertStringContainsString($this->translate('record_deleted'), $crawler->text());
    }
}
