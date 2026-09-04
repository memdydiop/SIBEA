<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('title')->comment('Nom du programme / lotissement ex: Les Jardins de Cocody');
            $table->string('slug')->unique()->comment('Identifiant URL unique, généré depuis title');
            $table->string('city')->nullable()->index()->comment('Ville principale ex: Abidjan');
            $table->string('municipality')->nullable()->comment('Commune / Municipality ex: Cocody');
            $table->decimal('total_area', 10, 2)->nullable()->comment('Surface totale en m² du lotissement');
            $table->string('district')->nullable()->comment('Quartier / District ex: Riviera Golf');
            $table->text('excerpt')->nullable()->comment('Résumé court pour cartes vitrine');
            $table->longText('description')->nullable()->comment('Description détaillée du programme, viabilisation, commodités');
            $table->string('cover_path')->nullable()->comment('Chemin image couverture ex: /storage/cms/programs/xxx.jpg');
            $table->integer('total_lots')->default(0)->comment('Nombre total de lots (dénormalisé, sync via lots count)');
            $table->boolean('is_published')->default(false)->index()->comment('Publié = visible vitrine');
            $table->timestamp('published_at')->nullable()->index()->comment('Date de publication programmée');
            $table->string('meta_title')->nullable()->comment('SEO meta title');
            $table->text('meta_description')->nullable()->comment('SEO meta description');
            $table->integer('order')->default(0)->comment("Ordre d'affichage vitrine");
            $table->timestamps();
            $table->softDeletes()->comment('Suppression douce pour historique');

            $table->index(['city', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
