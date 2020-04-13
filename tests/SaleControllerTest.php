<?php declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;


final class SaleControllerTest extends WebTestCase
{
    private $client;

    public function setUp() 
    {
        $this->client = static::createClient();          
    }

    public function testIndex()
    {
        $this->client->request('GET', '/sales/');
       
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Sales List');
        $this->assertSelectorTextContains('td', 'No records found');
    }

    public function testNewPostWithInvalidParam()
    {       
        $crawler = $this->client->request(
            'POST',
            '/sales/new',
            [
                'sale' => [
                    'quantity' => 'nonNumeric',
                    'price' => 'nonNumeric',                    
                ]
            ]
        );
                
        $errors =  $crawler
            ->filter('.form-error-message')
            ->each(fn(Crawler $node) => $node->text());

        foreach($errors as $error) {            
            $this->assertSame($error, 'This value is not valid.');
        }                
    }

    public function testNewGet()
    {        
        $this->client->request('GET', '/sales/new');        

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Create New Sale');
       
    }

    public function testNewPostWithNoStock()
    {
        $crawler = $this->client->request(
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
        $this->assertSame('No stock available for sale.', $crawler->filter('.form-error-message')->text());
    }

    public function testNewPost()
    {       
        $this->client->request(
            'POST',
            '/purchase/new',
            [
                'purchase' => [
                    'quantity' => 1,
                    'price' => 10,                    
                ]
            ]
        );
        
        $this->client->request(
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
            $this->client->getResponse()->isRedirect('/sales/')
        );
    }

    public function testIndexWithData()
    {
        $this->client->request(
            'POST',
            '/purchase/new',
            [
                'purchase' => [
                    'quantity' => 1,
                    'price' => 10,                    
                ]
            ]
        );

        $this->client->request(
            'POST',
            '/sales/new',
            [
                'sale' => [
                    'quantity' => 1,
                    'price' => 20,                   
                ]
            ]
        );
        
        $this->client->request('GET', '/sales/');
       
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Sales List');
        $this->assertSelectorTextNotContains('td', 'No records found');
    }

    public function testProfit()
    {        
        $this->client->request(
            'POST',
            '/purchase/new',
            [
                'purchase' => [
                    'quantity' => 1,
                    'price' => 10,                    
                ]
            ]
        );

        $this->client->request(
            'POST',
            '/sales/new',
            [
                'sale' => [
                    'quantity' => 1,
                    'price' => 20,                  
                ]
            ]
        );
        
        $this->client->request('GET', '/sales/profit');     

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Profit: 10');
       
    }
}
