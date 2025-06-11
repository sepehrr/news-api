<?php

namespace App\Console\Commands;

use App\Jobs\CrawlArticlesJob;
use Illuminate\Console\Command;

class CrawlArticlesCommand extends Command
{
    protected $signature = 'articles:crawl';

    protected $description = 'Crawl articles from configured sources';

    public function handle(): int
    {
        $this->info('Dispatching article crawl job...');

        try {
            CrawlArticlesJob::dispatch();
            $this->info('Article crawl job dispatched successfully.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error dispatching article crawl job: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
