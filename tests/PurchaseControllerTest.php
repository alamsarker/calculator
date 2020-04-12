<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/purchase/');
       
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Purchase List');
        $this->assertSelectorTextContains('td', 'No records found');
    }

    public function testNewGet()
    {
        $client = static::createClient();
        
        $client->request('GET', '/purchase/new');        

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Create New Purchase');       
    }

    public function testNewPost()
    {
        $client = static::createClient();
        $csrfToken = $client
            ->getContainer()
            ->get('security.csrf.token_manager')
            ->getToken('createPurchase')
            ;
        
        $client->request(
            'POST',
            '/purchase/new',
            [
                'purchase' => [
                    'quantity' => 1,
                    'price' => 10,
                   // '_token' => $csrfToken,
                ]
            ]
        );
        $this->assertTrue(
            $client->getResponse()->isRedirect('/purchase/')
        );
    }

    public function testIndexWithData()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/purchase/new',
            [
                'purchase' => [
                    'quantity' => 1,
                    'price' => 10,                  
                ]
            ]
        );

        $client->request('GET', '/purchase/');
       
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Purchase List');
        $this->assertSelectorTextNotContains('td', 'No records found');
    }
}
