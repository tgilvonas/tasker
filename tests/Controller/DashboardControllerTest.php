<?php

namespace App\Tests\Controller;

class DashboardControllerTest extends GenericControllerTestCase
{
    public function testDashboard(): void
    {
        $this->client->loginUser($this->superAdminUser);

        $this->client->request('GET', '/dashboard');

        $this->assertResponseIsSuccessful();
    }
}
