<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->boolean('longue_periode')->default(false)->after('statut');
            $table->date('date_fin_periode')->nullable()->after('longue_periode');
            $table->string('groupe_id')->nullable()->after('date_fin_periode'); // lie les réservations d'un même groupe
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['longue_periode', 'date_fin_periode', 'groupe_id']);
        });
    }
};
