<?php

namespace App\Tests\Controller;

class ProjectControllerTest extends GenericControllerTestCase
{
    public function testIndex(): void
    {
        $this->client->request('GET', '/projects');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/projects');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction(): void
    {
        $this->client->request('GET', '/projects/create');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/projects/create');
        $this->assertResponseIsSuccessful();

        $this->client->followRedirects();

        $buttonCrawlerNode = $crawler->selectButton('project_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['project_form[name]'] = 'Project 1';
        $form['project_form[description]'] = 'This is the description of Project 1.';
        $form['project_form[ord]'] = 1;
        $form['project_form[created_at]'] = date('Y-m-d');
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString('Project 1', $crawler->text());
    }
}
