<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Só adiciona role_title se ela ainda não existir
            if (!Schema::hasColumn('users', 'role_title')) {
                $table->string('role_title')->nullable()->after('email');
            }

            // Só adiciona department se ela ainda não existir
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('role_title');
            }

            // Só adiciona bio se ela ainda não existir (previne o erro!)
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('department');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'role_title')) {
                $columnsToDrop[] = 'role_title';
            }
            if (Schema::hasColumn('users', 'department')) {
                $columnsToDrop[] = 'department';
            }
            // Não removemos a 'bio' no rollback caso ela já existisse antes de nós
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};