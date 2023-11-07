<?php

namespace App\Tests\Controller;

class UserControllerTest extends GenericControllerTestCase
{
    public function testIndexAction(): void
    {
        $this->client->request('GET', '/users');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(403);

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($this->superAdminUser->getEmail(), $crawler->text());
    }
}
