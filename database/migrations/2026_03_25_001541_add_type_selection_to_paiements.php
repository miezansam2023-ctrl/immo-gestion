<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->enum('type_selection', ['simple', 'multiple'])
                ->default('simple')->after('mois_annee');
            $table->json('mois_concernes')->nullable()->after('type_selection');
        });
    }
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['type_selection', 'mois_concernes']);
        });
    }
};
