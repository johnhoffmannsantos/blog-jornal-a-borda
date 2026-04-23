<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostStatusEnum
{
    /**
     * Garante que a coluna `posts.status` (MySQL ENUM) inclua o valor "scheduled".
     * Idempotente: não executa ALTER se já estiver correto.
     */
    public static function ensureMysqlIncludesScheduled(): bool
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql' || ! Schema::hasTable('posts')) {
            return false;
        }

        $row = DB::selectOne('SHOW COLUMNS FROM `posts` WHERE Field = ?', ['status']);
        if (! $row || ! isset($row->Type)) {
            return false;
        }

        $type = (string) $row->Type;
        if (str_contains($type, "'scheduled'")) {
            return false;
        }

        DB::statement("ALTER TABLE `posts` MODIFY COLUMN `status` ENUM('draft', 'scheduled', 'published') NOT NULL DEFAULT 'published'");

        return true;
    }
}
