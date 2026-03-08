<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 191)->unique(); // Ex: INC-2024-001
            
            // Relations
            $table->foreignId('bien_id')->constrained('biens')->onDelete('cascade');
            $table->foreignId('contrat_id')->nullable()->constrained('contrats')->onDelete('set null');
            $table->foreignId('locataire_id')->nullable()->constrained('locataires')->onDelete('set null');
            $table->foreignId('gestionnaire_id')->constrained('users')->onDelete('cascade');
            
            // Type d'incident
            $table->enum('categorie', [
                'plomberie',
                'electricite',
                'climatisation',
                'menuiserie',
                'peinture',
                'toiture',
                'portail',
                'jardin',
                'autre'
            ]);
            
            // Détails
            $table->string('titre');
            $table->text('description');
            $table->enum('priorite', ['basse', 'moyenne', 'haute', 'urgente'])->default('moyenne');
            $table->enum('impact', ['mineur', 'moyen', 'majeur', 'critique'])->default('moyen');
            
            // Dates
            $table->dateTime('date_signalement');
            $table->dateTime('date_intervention')->nullable();
            $table->dateTime('date_resolution')->nullable();
            
            // Prestataire/Intervenant
            $table->string('prestataire_nom')->nullable();
            $table->string('prestataire_telephone')->nullable();
            $table->text('travaux_effectues')->nullable();
            
            // Coûts en FCFA
            $table->decimal('cout_estime', 12, 2)->nullable();
            $table->decimal('cout_reel', 12, 2)->nullable();
            $table->enum('charge_par', ['proprietaire', 'locataire', 'agence'])->nullable();
            
            // Statut
            $table->enum('statut', [
                'nouveau',
                'en_attente',
                'en_cours',
                'resolu',
                'annule',
                'reporte'
            ])->default('nouveau');
            
            // Documents
            $table->json('photos')->nullable(); // photos avant/après
            $table->json('documents')->nullable(); // devis, factures
            
            // Suivi
            $table->text('notes')->nullable();
            $table->json('historique')->nullable(); // historique des changements de statut
            
            // Satisfaction
            $table->integer('note_satisfaction')->nullable(); // 1-5
            $table->text('commentaire_satisfaction')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['statut', 'priorite']);
            $table->index(['bien_id', 'date_signalement']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};