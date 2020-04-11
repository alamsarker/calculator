<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/purchase/');
       
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('td', 'No records found');
    }

    public function testNew()
    {
        $client = static::createClient();
        
        $crawler = $client->request('POST', '/purchase/new', [
            'quantity' => 1,
            'price' => 10
        ]);

        $this->assertResponseIsSuccessful();

        //print_r($crawler);




        //$this->assertResponseRedirects('http://calculator_nginx_1/purchase/', 301);

       
        // $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('td', 'No records found');
    }
}
