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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Utilisateur à l\'origine de l\'action (FK users)');
            $table->string('action', 100)->comment('Action réalisée (create, update, delete, login, etc.)');
            $table->string('auditable_type')->nullable()->comment('Type du modèle audité (classe polymorphique)');
            $table->unsignedBigInteger('auditable_id')->nullable()->comment('Identifiant du modèle audité');
            $table->json('old_values')->nullable()->comment('Anciennes valeurs (JSON) avant modification');
            $table->json('new_values')->nullable()->comment('Nouvelles valeurs (JSON) après modification');
            $table->string('ip_address', 45)->nullable()->comment('Adresse IP de l\'auteur de l\'action');
            $table->text('user_agent')->nullable()->comment('User-Agent du navigateur');
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
