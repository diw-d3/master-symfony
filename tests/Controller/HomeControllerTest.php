<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Les produits les plus chers');

        $this->assertCount(4, $crawler->filter('.card'));

        $mailerCollector = $client->getProfile()->getCollector('mailer');
        $mail = $mailerCollector->getEvents()->getMessages()[0];

        $this->assertStringContainsString('Hello', $mail->getTextBody());
        $this->assertStringContainsString('toto@toto.com', $mail->getFrom()[0]->getAddress());

        $this->assertEmailCount(1);
    }

    public function testLoginPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'matthieumota@gmail.com',
            'password' => '123',
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertSame('matthieumota@gmail.com', $crawler->filter('.navbar-nav.ml-auto')->text());
    }
}
