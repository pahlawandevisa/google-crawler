<?php
namespace CViniciusSDias\GoogleCrawler\Tests\Unit;

use CViniciusSDias\GoogleCrawler\Crawler;
use CViniciusSDias\GoogleCrawler\Proxy\NoProxy;
use CViniciusSDias\GoogleCrawler\SearchTerm;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{

    public function testCrawlerGetsCorrectUrlWithSpecificDomain()
    {
        $crawler = new Crawler(new SearchTerm('Test'), new NoProxy(), 'www.google.com.br');
        $url = $this->getUrlFromCrawler($crawler);

        static::assertEquals('https://www.google.com.br/search?q=Test&num=100', $url);
    }

    public function testCrawlerGetsCorrectUrlWithCountryCode()
    {
        $crawler = new Crawler(new SearchTerm('Test'), new NoProxy(), 'www.google.com.br', 'BR');
        $url = $this->getUrlFromCrawler($crawler);

        static::assertEquals('https://www.google.com.br/search?q=Test&num=100&gl=BR', $url);
    }

    public function testDefaultCrawlerGetsCorrectUrl()
    {
        $crawler = new Crawler(new SearchTerm('Test'));
        $url = $this->getUrlFromCrawler($crawler);

        static::assertEquals('https://google.com/search?q=Test&num=100', $url);
    }

    public function testTryingToInstantiateACrawlerWithHttpOnGoogleDomainMustFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $domain = 'http://google.com';
        new Crawler(new SearchTerm(''), new NoProxy(), $domain);
    }

    public function testTryingToInstantiateACrawlerWithoutGoogleOnTheDomainMustFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Crawler(new SearchTerm(''), new NoProxy(), 'invalid-domain');
    }

    private function getUrlFromCrawler(Crawler $crawler): string
    {
        $reflectionClass = new \ReflectionClass($crawler);
        $reflectionMethod = $reflectionClass->getMethod('getGoogleUrl');
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($crawler);
    }
}
