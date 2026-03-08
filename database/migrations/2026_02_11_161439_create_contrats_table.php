<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 191)->unique(); // Ex: CONT-2024-001
            
            // Relations
            $table->foreignId('bien_id')->constrained('biens')->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->foreignId('gestionnaire_id')->constrained('users')->onDelete('cascade');
            
            // Dates du contrat
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('duree_mois'); // durée en mois
            $table->date('date_signature')->nullable();
            
            // Montants en FCFA
            $table->decimal('loyer_mensuel', 12, 2);
            $table->decimal('caution', 12, 2);
            $table->decimal('charges_mensuelles', 12, 2)->default(0);
            $table->decimal('frais_agence', 12, 2)->default(0);
            
            // Paiement
            $table->integer('jour_paiement')->default(1); // jour du mois pour le paiement
            $table->enum('mode_paiement', ['especes', 'virement', 'cheque', 'autre'])->default('especes');
            
            // Conditions spécifiques
            $table->boolean('animaux_autorises')->default(false);
            $table->boolean('sous_location_autorisee')->default(false);
            $table->text('conditions_particulieres')->nullable();
            
            // État des lieux
            $table->json('etat_lieux_entree')->nullable();
            $table->date('date_etat_lieux_entree')->nullable();
            $table->json('etat_lieux_sortie')->nullable();
            $table->date('date_etat_lieux_sortie')->nullable();
            
            // Signatures électroniques
            $table->text('signature_locataire')->nullable(); // Base64
            $table->text('signature_proprietaire')->nullable(); // Base64
            $table->text('signature_gestionnaire')->nullable(); // Base64
            
            // Renouvellement
            $table->boolean('renouvellement_automatique')->default(false);
            $table->integer('preavis_jours')->default(90); // préavis en jours
            
            // Statut
            $table->enum('statut', ['brouillon', 'actif', 'expire', 'resilie', 'archive'])->default('brouillon');
            $table->date('date_resiliation')->nullable();
            $table->text('motif_resiliation')->nullable();
            
            // Documents
            $table->string('fichier_pdf')->nullable();
            $table->json('documents_annexes')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};