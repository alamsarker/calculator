<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Feature test the Home Controller
 */
final class HomeControllerTest extends WebTestCase
{
    /**
     * Assert Home page
     */
    public function testHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Please see the usages note');
    }
}
