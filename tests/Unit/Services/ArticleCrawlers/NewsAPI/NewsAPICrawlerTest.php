<?php

namespace Tests\Unit\Services\ArticleCrawlers\NewsAPI;

use App\Models\Article;
use App\Models\Author;
use App\Models\Source;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Services\ArticleCrawlers\NewsAPI\NewsAPIClient;
use App\Services\ArticleCrawlers\NewsAPI\NewsAPICrawler;
use App\Services\Interfaces\ArticleServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class NewsAPICrawlerTest extends TestCase
{
    use RefreshDatabase;

    private NewsAPICrawler $crawler;

    private NewsAPIClient $mockClient;

    private Source $source;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(NewsAPIClient::class);
        $this->crawler = new NewsAPICrawler($this->mockClient, app(ArticleServiceInterface::class), app(SourceRepositoryInterface::class));
        $this->source = Source::factory()->create(['name' => $this->crawler->sourceName()]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_articles_returns_articles_from_client()
    {
        $expectedArticles = [
            [
                'title' => 'Test Article',
                'description' => 'Test Description',
                'publishedAt' => '2024-03-20T10:00:00Z',
                'url' => 'https://example.com/article',
                'author' => 'John Doe'
            ]
        ];

        $this->mockClient
            ->shouldReceive('getArticles')
            ->once()
            ->andReturn($expectedArticles);

        $result = $this->crawler->getArticles();

        $this->assertEquals($expectedArticles, $result);
    }

    public function test_create_article_creates_article_with_correct_data()
    {
        $articleData = [
            'title' => 'Test Article',
            'description' => 'Test Description',
            'publishedAt' => '2024-03-20T10:00:00Z',
            'url' => 'https://example.com/article',
            'author' => 'John Doe'
        ];

        $article = $this->crawler->createArticle($articleData);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals($articleData['title'], $article->title);
        $this->assertEquals($articleData['description'], $article->body);
        $this->assertEquals('2024-03-20 10:00:00', $article->published_at);
        $this->assertEquals(md5($articleData['url']), $article->external_id);
        $this->assertEquals($this->source->id, $article->source_id);

        $author = Author::where('name', 'John Doe')->first();
        $this->assertNotNull($author);
        $this->assertEquals($author->id, $article->author_id);
    }

    public function test_create_article_without_author_creates_article_without_author()
    {
        $articleData = [
            'title' => 'Test Article',
            'description' => 'Test Description',
            'publishedAt' => '2024-03-20T10:00:00Z',
            'url' => 'https://example.com/article'
        ];

        $article = $this->crawler->createArticle($articleData);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertNull($article->author_id);
    }

    public function test_create_article_without_description_uses_content()
    {
        $articleData = [
            'title' => 'Test Article',
            'content' => 'Test Content',
            'publishedAt' => '2024-03-20T10:00:00Z',
            'url' => 'https://example.com/article'
        ];

        $article = $this->crawler->createArticle($articleData);

        $this->assertEquals('Test Content', $article->body);
    }

    public function test_source_title_returns_correct_title()
    {
        $this->assertEquals('NewsAPI', $this->crawler->sourceName());
    }
}
