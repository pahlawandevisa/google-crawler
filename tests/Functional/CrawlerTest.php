<?php
namespace CViniciusSDias\GoogleCrawler;

use CViniciusSDias\GoogleCrawler\Proxy\CommonProxy;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
    public function testSearchResultsWithOutProxy()
    {
        $searchTerm = new SearchTerm('Test');
        $crawler = new Crawler($searchTerm);

        $results = $crawler->getResults();
        $this->checkResults($results);
    }

    /**
     * @dataProvider getCommonEndpoints
     * @param string $endpoint
     */
    public function testSearchResultsWithCommonProxy(string $endpoint)
    {
        $commonProxy = new CommonProxy($endpoint);
        $searchTerm = new SearchTerm('Test');
        $crawler = new Crawler($searchTerm, $commonProxy);
        $results = $crawler->getResults();

        $this->checkResults($results);
    }

    public function getCommonEndpoints(): array
    {
        return [
            ['https://proxy-us.hideproxy.me/includes/process.php?action=update'],
            ['https://proxy-nl.hideproxy.me/includes/process.php?action=update'],
            ['https://proxy-de.hideproxy.me/includes/process.php?action=update']
        ];
    }

    public function checkResults(ResultList $results): void
    {
        static::assertNotEmpty($results->getResults());

        /** @var Result $result */
        foreach ($results as $result) {
            static::assertInstanceOf(Result::class, $result);
            static::assertTrue(filter_var($result->getUrl(), FILTER_VALIDATE_URL) !== false);
            static::assertNotEmpty($result->getTitle());
        }
    }
}
