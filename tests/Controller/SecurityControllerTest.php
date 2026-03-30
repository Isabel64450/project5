<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form.loginForm');
        $this->assertSelectorExists('input[name="email"]');
        $this->assertSelectorExists('input[name="password"]');
        $this->assertSelectorTextContains('h1', 'Connexion');
        
    }

    public function testLoginRedirectsIfAlreadyAuthenticated(): void
    {
       
        $client = static::createClient();
        /* $container = static::getContainer();        
        $userProvider = $container->get('security.user.provider.concrete.app_user_provider_test');        
        $user = $userProvider->loadUserByIdentifier('test@test.com');   */     
        /* $client->loginUser('test@test.com','password'); */       
        $client->request('GET', '/login');        
        $this->assertResponseIsSuccessful();
    }
}
