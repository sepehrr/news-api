<?php

namespace App\Console\Commands;

use App\Services\ArticleCrawlers\ArticleCrawlerService;
use Illuminate\Console\Command;

class CrawlArticles extends Command
{
    protected $signature = 'articles:crawl';

    protected $description = 'Crawl articles from configured sources';

    public function __construct(
        private ArticleCrawlerService $crawlerService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting article crawl...');

        try {
            $this->crawlerService->crawl();
            $this->info('Article crawl completed successfully.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during article crawl: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
