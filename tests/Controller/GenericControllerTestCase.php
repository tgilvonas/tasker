<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GenericControllerTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected ?object $userRepository;

    protected User $superAdminUser;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->userRepository = static::getContainer()->get(UserRepository::class);

        $this->superAdminUser = $this->userRepository->findOneByEmail('peter@tasker.com');

        $this->user = $this->userRepository->findOneByEmail('john@tasker.com');
    }
}