<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Feature test the Sale Controller
 */
final class SaleControllerTest extends WebTestCase
{
    /**
     * @var private @client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Test the sales list page where no data found.
     */
    public function testIndexWithNoData()
    {
        $crawler = $this->client->request('GET', '/sales/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Sales List');
        $this->assertSame('Create New', $crawler->filter('.ft-create-new-link')->text());
        $this->assertSame('Quantity', $crawler->filter('thead > tr')->children()->eq(0)->text());
        $this->assertSame('Price', $crawler->filter('thead > tr')->children()->eq(1)->text());
        $this->assertSelectorTextContains('td', 'No records found');
    }

    /**
     * Test the sales list page that check data show properly.
     */
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

    /**
     * Test the sale create page shows properly.
     */
    public function testNewGet()
    {
        $crawler = $this->client->request('GET', '/sales/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Create New Sale');
        $this->assertSame('Back', $crawler->filter('.ft-back-link')->text());
    }

    /**
     * Test creating a new sale with in valid param.
     * It should show error message.
     */
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
            ->each(fn(Crawler $node) => $node->text())
            ;

        foreach ($errors as $error) {
            $this->assertSame($error, 'This value is not valid.');
        }
    }

    /**
     * Test no stock available for sales.
     */
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

    /**
     * Test creating a new sale.
     */
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

    /**
     * Test generating profit properly.
     */
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
        $this->assertSelectorTextContains('title', 'Sales Margin Profit');
        $this->assertSelectorTextContains('h3', 'Profit: 10');
    }
}
