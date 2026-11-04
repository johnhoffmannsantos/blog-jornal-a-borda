<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration defensiva e idempotente.
 *
 * O UserController aceita as roles: admin, editor, author, reviewer,
 * social_media, communication, designer e grava a coluna "department".
 * Mas a migration original (add_role_to_users_table) criou "role" como
 * ENUM('admin','editor','author'), o que rejeitaria "reviewer" e as demais.
 *
 * - Converte "role" para VARCHAR(50) (somente MySQL/MariaDB; seguro se já for varchar).
 * - Cria "department" apenas se ainda não existir.
 *
 * Se o seu banco de produção já está correto, esta migration não altera nada
 * de relevante.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role') && in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(50) NOT NULL DEFAULT 'author'");
        }

        if (! Schema::hasColumn('users', 'department')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('department', 100)->nullable()->after('position');
            });
        }
    }

    public function down(): void
    {
        // Intencionalmente vazio: reverter para ENUM poderia quebrar usuários
        // com roles novas (reviewer, social_media, communication, designer).
    }
};
