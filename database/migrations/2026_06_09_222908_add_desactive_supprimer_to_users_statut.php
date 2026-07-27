<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Compatible MySQL uniquement (SQLite ne supporte pas ENUM MODIFY)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN statut ENUM('en_cours','valide','rejete','desactive','supprime') DEFAULT 'en_cours'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN statut ENUM('en_cours','valide','rejete') DEFAULT 'en_cours'");
        }
    }
};
