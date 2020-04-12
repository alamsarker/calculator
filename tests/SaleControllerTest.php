<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SaleControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/sales/');
       
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Sales List');
        $this->assertSelectorTextContains('td', 'No records found');
    }

    public function testNewGet()
    {
        $client = static::createClient();
        
        $client->request('GET', '/sales/new');        

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Create New Sale');
       
    }

    public function testNewPostWithNoStock()
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/sales/new',
            [
                'sale' => [
                    'quantity' => 1,
                    'price' => 20,                   
                ]
            ]
        );
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Create New Sale');        
    }

    public function testNewPost()
    {
        $client = static::createClient();
        $csrfToken = $client
            ->getContainer()
            ->get('security.csrf.token_manager')
            ->getToken('createSale')
            ;

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
        
        $client->request(
            'POST',
            '/sales/new',
            [
                'sale' => [
                    'quantity' => 1,
                    'price' => 20,                   
                ]
            ]
        );
        
        $this->assertTrue(
            $client->getResponse()->isRedirect('/sales/')
        );
    }

    public function testIndexWithData()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/purchase/new',
            [
                'purchase' => [
                    'quantity' => 1,
                    'price' => 10,                    
                ]
            ]
        );

        $client->request(
            'POST',
            '/sales/new',
            [
                'sale' => [
                    'quantity' => 1,
                    'price' => 20,                   
                ]
            ]
        );

        
        $client->request('GET', '/sales/');
       
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Sales List');
        $this->assertSelectorTextNotContains('td', 'No records found');
    }

    public function testProfit()
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

        $client->request(
            'POST',
            '/sales/new',
            [
                'sale' => [
                    'quantity' => 1,
                    'price' => 20,                  
                ]
            ]
        );
        
        $client->request('GET', '/sales/profit');     

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Profit: 10');
       
    }
}
