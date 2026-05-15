<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        DB::table('posts')->where('status', 'scheduled')->update(['status' => 'published']);

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `posts` MODIFY COLUMN `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published'");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `posts` MODIFY COLUMN `status` ENUM('draft', 'scheduled', 'published') NOT NULL DEFAULT 'published'");
        }
    }
};
