<?php

use App\Support\PostStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddScheduledStatusToPostsTable extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            PostStatusEnum::ensureMysqlIncludesScheduled();
        }
        // SQLite: coluna enum do Laravel já é string; novos valores são aceitos sem ALTER.
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("UPDATE posts SET status = 'draft' WHERE status = 'scheduled'");
            DB::statement("ALTER TABLE posts MODIFY COLUMN status ENUM('draft', 'published') NOT NULL DEFAULT 'published'");
        }
    }
}
