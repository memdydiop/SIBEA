<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('is_public')->comment('URL de l\'avatar / photo de l\'employé');
            $table->integer('vitrine_order')->default(0)->after('avatar_url')->comment('Ordre d\'affichage sur la vitrine équipe');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['avatar_url', 'vitrine_order']);
        });
    }
};
