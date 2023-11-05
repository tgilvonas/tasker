<?php

namespace App\Tests\Controller;

class TaskControllerTest extends GenericControllerTestCase
{
    public function testIndex(): void
    {
        $this->client->loginUser($this->superAdminUser);

        $crawler = $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }
}
