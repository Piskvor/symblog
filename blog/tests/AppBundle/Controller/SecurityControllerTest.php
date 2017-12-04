<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testPrivateRedirect()
    {
        $client = static::createClient();

        $client->request('GET', '/private');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    public function testLoginForm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Log in', $crawler->filter('form button')->text());
    }
}
