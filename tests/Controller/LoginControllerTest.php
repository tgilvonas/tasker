<?php

namespace App\Tests\Controller;

class LoginControllerTest extends GenericControllerTestCase
{
    public function testIfLoginPageWorks(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testIfLogoutFunctionWorks(): void
    {
        $this->client->loginUser($this->superAdminUser);

        $this->client->request('GET', '/logout');

        $this->assertResponseRedirects('/');
    }
}
