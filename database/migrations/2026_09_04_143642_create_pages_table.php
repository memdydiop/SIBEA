<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('slug')->unique()->comment('Identifiant URL unique de la page');
            $table->string('title')->comment('Titre de la page');
            $table->string('meta_title')->nullable()->comment('Titre SEO (balise meta title)');
            $table->text('meta_description')->nullable()->comment('Description SEO (meta description)');
            $table->text('excerpt')->nullable()->comment('Résumé / extrait de la page');
            $table->longText('content')->nullable()->comment('Contenu complet de la page (HTML/Markdown)');
            $table->string('cover_image')->nullable()->comment('Image de couverture de la page');
            $table->boolean('is_published')->default(false)->index()->comment('Page publiée / visible');
            $table->timestamp('published_at')->nullable()->index()->comment('Date de publication');
            $table->integer('order')->default(0)->index()->comment('Ordre d\'affichage');
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
