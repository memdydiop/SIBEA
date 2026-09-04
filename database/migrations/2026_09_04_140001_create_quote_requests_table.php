<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('reference')->unique()->comment('Référence unique de la demande de devis');
            $table->string('first_name')->comment('Prénom du demandeur');
            $table->string('last_name')->comment('Nom du demandeur');
            $table->string('company')->nullable()->comment('Entreprise / société du demandeur');
            $table->string('role')->nullable()->comment('Fonction / rôle du demandeur');
            $table->string('email')->index()->comment('Adresse e-mail du demandeur');
            $table->string('phone')->comment('Numéro de téléphone du demandeur');
            $table->string('location')->nullable()->comment('Localisation du projet / du demandeur');
            $table->string('service_type')->index()->comment('Type de service demandé');
            $table->string('project_nature')->nullable()->comment('Nature du projet');
            $table->string('estimated_budget')->nullable()->comment('Budget estimé du projet');
            $table->string('desired_timeline')->nullable()->comment('Délai souhaité pour le projet');
            $table->text('description')->comment('Description détaillée du besoin');
            $table->string('status')->default('nouveau')->index()->comment('Statut de la demande (nouveau, en_cours, traite, archive)');
            $table->boolean('consent')->default(true)->comment('Consentement RGPD pour le traitement des données');
            $table->text('internal_notes')->nullable()->comment('Notes internes de traitement');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->comment('Utilisateur assigné au traitement (FK users)');
            $table->timestamps();
            $table->softDeletes()->comment('Date de suppression douce');

            $table->index(['status', 'created_at']);
            $table->index(['assigned_to', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
