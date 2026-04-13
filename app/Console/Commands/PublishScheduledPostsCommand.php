<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class PublishScheduledPostsCommand extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description = 'Publica posts com status agendado cuja data de publicação já passou';

    public function handle(): int
    {
        $count = Post::query()
            ->where('status', 'scheduled')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        Cache::put('scheduler_posts_publish_last_at', now()->toIso8601String(), now()->addDays(30));

        $this->info("Posts publicados: {$count}");

        return self::SUCCESS;
    }
}
