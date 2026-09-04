<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('collection')->default('default')->comment('Collection du média (default, cms, avatar, hero)');
            $table->string('model_type')->nullable()->comment('Type du modèle lié (polymorphique)');
            $table->unsignedBigInteger('model_id')->nullable()->comment('Identifiant du modèle lié');
            $table->string('file_name')->comment('Nom original du fichier');
            $table->string('file_path')->comment('Chemin de stockage du fichier');
            $table->string('mime_type')->nullable()->comment('Type MIME du fichier');
            $table->unsignedBigInteger('size')->nullable()->comment('Taille du fichier en octets');
            $table->string('alt')->nullable()->comment('Texte alternatif pour accessibilité / SEO');
            $table->integer('order')->default(0)->comment('Ordre d\'affichage dans la collection');
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
