<?php declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

final class PurchaseControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndexWithNoData()
    {
        $this->client->request('GET', '/purchase/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Purchase List');
        $this->assertSelectorTextContains('td', 'No records found');
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

        $this->client->request('GET', '/purchase/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Purchase List');
        $this->assertSelectorTextNotContains('td', 'No records found');
    }

    public function testNewGet()
    {
        $this->client->request('GET', '/purchase/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Create New Purchase');
    }

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

        foreach($errors as $error) {
            $this->assertSame($error, 'This value is not valid.');
        }
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

        $this->assertTrue(
            $this->client->getResponse()->isRedirect('/purchase/')
        );
    }
}
