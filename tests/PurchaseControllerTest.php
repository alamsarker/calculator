<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Feature test the Purchase Controller.
 */
final class PurchaseControllerTest extends WebTestCase
{
    /**
     * @var private $client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Test the purchase list page where no data found.
     */
    public function testIndexWithNoData()
    {
        $crawler = $this->client->request('GET', '/purchase/');

        $this->assertSame('Create New', $crawler->filter('.ft-create-new-link')->text());
        $this->assertSame('Quantity', $crawler->filter('thead > tr')->children()->eq(0)->text());
        $this->assertSame('Price', $crawler->filter('thead > tr')->children()->eq(1)->text());
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Purchase List');
        $this->assertSelectorTextContains('td', 'No records found');
    }

    /**
     * Test the purchase list page that check data show properly.
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

        $this->client->request('GET', '/purchase/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Purchase List');
        $this->assertSelectorTextNotContains('td', 'No records found');
    }

    /**
     * Test the purchase create page shows properly.
     */
    public function testNewGet()
    {
        $crawler = $this->client->request('GET', '/purchase/new');

        $this->assertResponseIsSuccessful();
        $this->assertSame('Back', $crawler->filter('.ft-back-link')->text());
        $this->assertSelectorTextContains('title', 'Create New Purchase');
    }

    /**
     * Test creating a new purchase with in valid param.
     * It should show error message.
     */
    public function testNewPostWithInvalidParam()
    {
        $crawler = $this->client->request(
            'POST',
            '/purchase/new',
            [
                'purchase' => [
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
     * Test creating a new purchase.
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

        $this->assertTrue(
            $this->client->getResponse()->isRedirect('/purchase/')
        );
    }
}
