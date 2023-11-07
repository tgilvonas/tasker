<?php

namespace App\Tests\Controller;

class DashboardControllerTest extends GenericControllerTestCase
{

    public function testDashboard(): void
    {
        $this->client->request('GET', '/dashboard');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->loginUser($this->superAdminUser);
        $this->client->request('GET', '/dashboard');
        $this->assertResponseIsSuccessful();

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/dashboard');
        $this->assertResponseIsSuccessful();
    }
}
