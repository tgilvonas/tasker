<?php

namespace App\Tests\Controller;

class ProjectControllerTest extends GenericControllerTestCase
{
    public function testIndex(): void
    {
        $this->client->loginUser($this->superAdminUser);

        $crawler = $this->client->request('GET', '/projects');

        $this->assertResponseIsSuccessful();
    }
}
