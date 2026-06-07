<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('matiere_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('classe_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Matiere::class);
            $table->dropForeignIdFor(\App\Models\Classe::class);
        });
    }
};