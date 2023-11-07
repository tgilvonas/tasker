<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;

class UserControllerTest extends GenericControllerTestCase
{
    protected ?object $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

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

    public function testCreateAction(): void
    {
        $this->client->request('GET', '/users/create');
        $this->assertResponseRedirects($this->baseUrl . '/');

        $this->client->followRedirects();

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(403);
        $this->client->request('POST', '/users/create');
        $this->assertResponseStatusCodeSame(403);

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('user_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['user_form[email]'] = 'jane@tasker.com';
        $form['user_form[password][first]'] = 'pa55word123';
        $form['user_form[password][second]'] = 'pa55word123';
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString($this->translate('record_created'), $crawler->text());
    }

    public function testUpdateAction(): void
    {
        $this->client->followRedirects();

        $user = $this->userRepository->findOneByEmail('jane@tasker.com');

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/users/update/' . $user->getId());
        $this->assertResponseStatusCodeSame(403);

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('GET', '/users/update/' . $user->getId());
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('user_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['user_form[email]'] = 'jane@tasker.com';
        $form['user_form[password][first]'] = 'pa55word123456';
        $form['user_form[password][second]'] = 'pa55word123456';
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString($this->translate('record_updated'), $crawler->text());

        $this->client->request('GET', '/projects/update/' . $user->getId());
    }

    public function testDeleteAction(): void
    {
        $user = $this->userRepository->findOneByEmail('jane@tasker.com');

        $this->client->followRedirects();

        $this->client->loginUser($this->user);
        $this->client->request('POST', '/users/delete/' . $user->getId());
        $this->assertResponseStatusCodeSame(403);

        $this->client->loginUser($this->superAdminUser);
        $crawler = $this->client->request('POST', '/users/delete/' . $user->getId());
        $this->assertStringContainsString($this->translate('record_deleted'), $crawler->text());
    }

    public function testUserProfile(): void
    {
        $this->client->followRedirects();

        $this->client->loginUser($this->user);
        $crawler = $this->client->request('GET', '/profile');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('user_profile_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['user_profile_form[email]'] = 'john@tasker.com';
        $form['user_profile_form[password][first]'] = 'pa55word123456789';
        $form['user_profile_form[password][second]'] = 'pa55word123456789';
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString($this->translate('profile_updated'), $crawler->text());
    }
}
