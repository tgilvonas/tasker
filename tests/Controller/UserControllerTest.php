<?php

namespace App\Tests\Controller;

class UserControllerTest extends GenericControllerTestCase
{
    public function testUsersControllerIndex(): void
    {
        $this->client->loginUser($this->superAdminUser);

        $crawler = $this->client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($this->superAdminUser->getEmail(), $crawler->text());
    }
}
