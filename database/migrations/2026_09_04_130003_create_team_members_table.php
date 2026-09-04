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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete()->comment('Équipe concernée (FK teams)');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete()->comment('Employé membre (FK employees)');
            $table->string('role_in_team')->nullable()->comment('Rôle de l\'employé au sein de l\'équipe');
            $table->date('joined_at')->nullable()->comment('Date d\'entrée dans l\'équipe');
            $table->date('left_at')->nullable()->comment('Date de sortie de l\'équipe');
            $table->boolean('is_active')->default(true)->comment('Appartenance active à l\'équipe');
            $table->timestamps();

            $table->unique(['team_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
