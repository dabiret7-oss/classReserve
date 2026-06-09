<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN statut ENUM('en_cours','valide','rejete','desactive','supprime') DEFAULT 'en_cours'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN statut ENUM('en_cours','valide','rejete') DEFAULT 'en_cours'");
    }
};