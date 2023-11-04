<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    public function testGetUsersIndexQueryFunction(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->assertTrue($userRepository->getUsersIndexQuery() instanceof Query);
    }
}
