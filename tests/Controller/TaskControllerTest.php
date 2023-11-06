<?php

namespace App\Tests\Controller;

class TaskControllerTest extends GenericControllerTestCase
{
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
    }
}
