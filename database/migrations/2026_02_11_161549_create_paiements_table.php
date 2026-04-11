<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 191)->unique();
 
            // Relations
            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->foreignId('bien_id')->constrained('biens')->onDelete('cascade');
            $table->foreignId('gestionnaire_id')->nullable()
                  ->constrained('users')->onDelete('set null');
 
            // Type de paiement
            $table->enum('type', [
                'loyer', 'caution', 'charges', 'eau', 'electricite',
                'frais_agence', 'reparation', 'penalite', 'autre'
            ]);
 
            // Période concernée
            $table->date('periode_debut')->nullable();
            $table->date('periode_fin')->nullable();
            $table->string('mois_annee')->nullable();
            $table->enum('type_selection', ['simple', 'multiple'])->default('simple');
            $table->json('mois_concernes')->nullable();
 
            // Montants en FCFA
            $table->decimal('montant_du', 12, 2);
            $table->decimal('montant_paye', 12, 2);
            $table->decimal('reste_a_payer', 12, 2)->default(0);
 
            // Détails du paiement
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();
            $table->enum('mode_paiement', ['especes', 'virement', 'cheque', 'autre'])->nullable();
            $table->string('reference_paiement')->nullable();
 
            // Statut
            $table->enum('statut', ['en_attente', 'paye', 'partiel', 'retard', 'annule'])
                  ->default('en_attente');
 
            // Retard et pénalités
            $table->integer('jours_retard')->default(0);
            $table->decimal('penalite', 12, 2)->default(0);
 
            // Quittance
            $table->string('numero_quittance')->nullable();
            $table->string('fichier_quittance')->nullable();
            $table->boolean('quittance_generee')->default(false);
            $table->date('date_generation_quittance')->nullable();
 
            // Informations complémentaires
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('fichier_recu')->nullable();
 
            $table->timestamps();
            $table->softDeletes();
 
            $table->index(['statut', 'date_echeance']);
            $table->index(['contrat_id', 'periode_debut']);
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
