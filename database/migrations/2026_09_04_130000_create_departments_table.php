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
        Schema::create('departments', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('parent_id')->nullable()->constrained('departments')->nullOnDelete()->comment('Département parent (hiérarchie, FK departments)');
            $table->string('name')->comment('Nom du département');
            $table->string('code')->unique()->comment('Code unique du département');
            $table->text('description')->nullable()->comment('Description du département');
            $table->unsignedBigInteger('manager_id')->nullable()->comment('Responsable du département (FK employees)');
            $table->boolean('is_active')->default(true)->index()->comment('Département actif (visible / utilisable)');
            $table->timestamps();

            $table->index(['parent_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
