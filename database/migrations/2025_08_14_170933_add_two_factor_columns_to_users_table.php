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
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')->after('password')->nullable()->comment('Secret chiffré pour l\'authentification à deux facteurs');
            $table->text('two_factor_recovery_codes')->after('two_factor_secret')->nullable()->comment('Codes de récupération 2FA chiffrés (JSON)');
            $table->timestamp('two_factor_confirmed_at')->after('two_factor_recovery_codes')->nullable()->comment('Date de confirmation de la configuration 2FA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
            ]);
        });
    }
};
