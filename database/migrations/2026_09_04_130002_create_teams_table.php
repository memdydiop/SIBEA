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
        Schema::create('teams', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete()->comment('Département de rattachement (FK departments)');
            $table->string('name')->comment('Nom de l\'équipe');
            $table->string('code')->unique()->comment('Code unique de l\'équpe');
            $table->foreignId('leader_id')->nullable()->constrained('employees')->nullOnDelete()->comment('Chef d\'équipe (FK employees)');
            $table->text('description')->nullable()->comment('Description de l\'équipe');
            $table->boolean('is_active')->default(true)->index()->comment('Équipe active');
            $table->timestamps();

            $table->index(['department_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
