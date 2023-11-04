<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUsersControllerIndex(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail('peter@tasker.com');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($user->getEmail(), $crawler->text());
    }
}
