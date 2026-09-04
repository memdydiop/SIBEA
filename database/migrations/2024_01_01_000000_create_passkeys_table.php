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
        Schema::create('passkeys', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->comment('Utilisateur propriétaire du passkey (FK users)');
            $table->string('name')->comment('Nom donné au passkey (ex: iPhone, YubiKey)');
            $table->string('credential_id')->unique()->comment('Identifiant unique de la credential WebAuthn');
            $table->json('credential')->comment('Données de la credential WebAuthn (JSON)');
            $table->timestamp('last_used_at')->nullable()->comment('Date de dernière utilisation du passkey');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passkeys');
    }
};
