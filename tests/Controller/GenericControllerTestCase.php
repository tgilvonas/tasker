<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class GenericControllerTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected string $baseUrl;

    protected string $httpHost;

    protected ?object $userRepository;

    protected User $superAdminUser;

    protected User $user;

    protected object $translator;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpHost = $_ENV['HTTP_HOST'];
        $this->baseUrl = $_ENV['BASE_URL'];

        $this->client = static::createClient([], ['HTTP_HOST' => $this->httpHost]);

        $this->userRepository = static::getContainer()->get(UserRepository::class);

        $this->superAdminUser = $this->userRepository->findOneByEmail('peter@tasker.com');

        $this->user = $this->userRepository->findOneByEmail('john@tasker.com');

        $this->translator = static::getContainer()->get(TranslatorInterface::class);
    }

    protected function translate(string $translationKey, string $domain = 'app'): string
    {
        return $this->translator->trans($translationKey, [], $domain);
    }
}