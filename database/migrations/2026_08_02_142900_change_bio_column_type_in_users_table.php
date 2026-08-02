<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Altera o tipo da coluna 'bio' para TEXT
            $table->text('bio')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Caso precise reverter, volta para VARCHAR(255)
            $table->string('bio', 255)->nullable()->change();
        });
    }
};