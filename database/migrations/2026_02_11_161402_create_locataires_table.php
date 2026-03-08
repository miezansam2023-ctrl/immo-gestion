<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locataires', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 191)->unique(); // Ex: LOC-2024-001
            
            // Informations personnelles
            $table->enum('civilite', ['M', 'Mme', 'Mlle']);
            $table->string('nom');
            $table->string('prenoms');
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->enum('situation_matrimoniale', ['celibataire', 'marie', 'divorce', 'veuf'])->nullable();
            
            // Contact
            $table->string('telephone');
            $table->string('telephone_secondaire')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse_precedente')->nullable();
            
            // Documents d'identité
            $table->enum('type_piece', ['cni', 'passeport', 'attestation_identite']);
            $table->string('numero_piece', 191)->unique();
            $table->date('date_delivrance_piece')->nullable();
            $table->date('date_expiration_piece')->nullable();
            $table->string('lieu_delivrance_piece')->nullable();
            
            // Informations professionnelles
            $table->string('profession')->nullable();
            $table->string('employeur')->nullable();
            $table->string('adresse_employeur')->nullable();
            $table->string('telephone_employeur')->nullable();
            $table->decimal('revenus_mensuels', 12, 2)->nullable();
            
            // Contact d'urgence
            $table->string('personne_urgence_nom')->nullable();
            $table->string('personne_urgence_telephone')->nullable();
            $table->string('personne_urgence_lien')->nullable();
            
            // Documents
            $table->json('documents')->nullable(); // Copie CNI, bulletins salaire, etc.
            $table->string('photo')->nullable();
            
            // Statut
            $table->boolean('actif')->default(true);
            $table->text('notes')->nullable();
            
            $table->foreignId('gestionnaire_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locataires');
    }
};