<?php

namespace App\Console\Commands;

use App\Support\PostStatusEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class FixPostStatusEnumCommand extends Command
{
    protected $signature = 'posts:fix-status-enum';

    protected $description = 'Ajusta o ENUM da coluna posts.status no MySQL para incluir o valor "scheduled" (idempotente; uso quando migrate falha ou fica parado)';

    public function handle(): int
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'mysql') {
            $this->comment("Driver de banco: {$driver}. Nenhum ALTER necessário (apenas MySQL usa ENUM rígido).");

            return self::SUCCESS;
        }

        if (! Schema::hasTable('posts')) {
            $this->error('A tabela `posts` não existe. Rode as migrations anteriores antes.');

            return self::FAILURE;
        }

        if (PostStatusEnum::ensureMysqlIncludesScheduled()) {
            $this->info('Concluído: coluna `posts.status` agora inclui o valor `scheduled`.');

            return self::SUCCESS;
        }

        $this->info('Nada a fazer: a coluna `posts.status` já inclui `scheduled`.');

        return self::SUCCESS;
    }
}
