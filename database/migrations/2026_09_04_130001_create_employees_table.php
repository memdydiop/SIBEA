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
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete()->comment('Compte utilisateur lié (FK users, optionnel)');
            $table->foreignId('department_id')->nullable()->constrained('departments')->restrictOnDelete()->comment('Département d\'affectation (FK departments)');
            $table->string('registration_number')->unique()->comment('Matricule unique de l\'employé (format EMP-XXXX-XXXX)');
            $table->string('first_name')->comment('Prénom de l\'employé');
            $table->string('last_name')->comment('Nom de famille de l\'employé');
            $table->string('email')->nullable()->index()->comment('Adresse e-mail professionnelle');
            $table->string('phone')->nullable()->comment('Numéro de téléphone');
            $table->string('job_title')->index()->comment('Intitulé du poste / fonction');
            $table->string('contract_type')->default('cdi')->index()->comment('Type de contrat (cdi, cdd, stage, etc.)');
            $table->string('status')->default('actif')->index()->comment('Statut de l\'employé (actif, inactif, suspendu)');
            $table->date('hire_date')->nullable()->index()->comment('Date d\'embauche');
            $table->date('end_date')->nullable()->comment('Date de fin de contrat / départ');
            $table->decimal('hourly_cost_rate', 10, 2)->nullable()->comment('Coût horaire en FCFA');
            $table->decimal('daily_cost_rate', 10, 2)->nullable()->comment('Coût journalier en FCFA');
            $table->string('emergency_contact_name')->nullable()->comment('Nom du contact d\'urgence');
            $table->string('emergency_contact_phone')->nullable()->comment('Téléphone du contact d\'urgence');
            $table->text('notes')->nullable()->comment('Notes internes sur l\'employé');
            $table->timestamps();
            $table->softDeletes()->comment('Date de suppression douce');

            $table->index(['department_id', 'status']);
            $table->index(['status', 'contract_type']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('employees')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });

        Schema::dropIfExists('employees');
    }
};
