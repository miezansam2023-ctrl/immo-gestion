<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 191)->unique(); // Ex: BIE-2024-001
            $table->enum('type', ['villa', 'appartement', 'studio', 'magasin', 'bureau', 'terrain']);
            $table->string('titre');
            $table->text('description')->nullable();
            
            // Adresse
            $table->string('adresse');
            $table->string('commune'); // Cocody, Plateau, Marcory, etc.
            $table->string('quartier');
            $table->string('ville')->default('Abidjan');
            
            // Caractéristiques
            $table->integer('nombre_pieces')->nullable();
            $table->integer('nombre_chambres')->nullable();
            $table->integer('nombre_salles_bain')->nullable();
            $table->decimal('superficie', 10, 2)->nullable(); // en m²
            $table->integer('etage')->nullable();
            $table->boolean('meuble')->default(false);
            
            // Équipements
            $table->json('equipements')->nullable(); // climatisation, garage, jardin, piscine, etc.
            
            // Prix
            $table->decimal('prix_loyer', 12, 2); // en FCFA
            $table->decimal('prix_caution', 12, 2)->nullable(); // généralement 2-3 mois de loyer
            $table->decimal('charges', 12, 2)->default(0); // charges mensuelles
            
            // Propriétaire
            $table->string('nom_proprietaire');
            $table->string('telephone_proprietaire')->nullable();
            $table->string('email_proprietaire')->nullable();
            
            // Statut
            $table->enum('statut', ['disponible', 'occupe', 'maintenance', 'reserve'])->default('disponible');
            
            // Photos et documents
            $table->json('photos')->nullable();
            $table->json('documents')->nullable();
            
            // Gestion
            $table->foreignId('gestionnaire_id')->constrained('users')->onDelete('cascade');
            $table->date('date_acquisition')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};