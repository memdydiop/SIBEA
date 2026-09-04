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
        Schema::create('expertises', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('slug')->unique()->comment('Identifiant URL unique de l\'expertise');
            $table->string('title')->comment('Titre de l\'expertise');
            $table->text('excerpt')->nullable()->comment('Résumé court de l\'expertise');
            $table->text('content')->nullable()->comment('Contenu détaillé de l\'expertise');
            $table->string('icon')->nullable()->comment('Icône associée (classe ou chemin)');
            $table->string('cover_image')->nullable()->comment('Image de couverture de l\'expertise');
            $table->integer('order')->default(0)->index()->comment('Ordre d\'affichage');
            $table->boolean('is_active')->default(true)->index()->comment('Expertise active / visible');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('expertise_id')->nullable()->constrained('expertises')->nullOnDelete()->comment('Expertise parente (FK expertises)');
            $table->string('slug')->unique()->comment('Identifiant URL unique du service');
            $table->string('title')->comment('Titre du service');
            $table->text('excerpt')->nullable()->comment('Résumé court du service');
            $table->text('content')->nullable()->comment('Contenu détaillé du service');
            $table->string('icon')->nullable()->comment('Icône associée au service');
            $table->integer('order')->default(0)->index()->comment('Ordre d\'affichage');
            $table->boolean('is_active')->default(true)->index()->comment('Service actif / visible');
            $table->timestamps();

            $table->index(['expertise_id', 'is_active']);
        });

        Schema::create('public_projects', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('slug')->unique()->comment('Identifiant URL unique du projet');
            $table->string('title')->comment('Titre du projet vitrine');
            $table->string('category')->index()->comment('Catégorie du projet (ex: BTP, voirie, bâtiment)');
            $table->string('client_name')->nullable()->comment('Nom du client / maître d\'ouvrage');
            $table->string('location')->nullable()->comment('Localisation du projet (ville, quartier)');
            $table->integer('year')->nullable()->index()->comment('Année de réalisation');
            $table->text('description')->nullable()->comment('Description détaillée du projet');
            $table->json('key_figures')->nullable()->comment('Chiffres clés du projet (JSON: surface, budget, durée)');
            $table->string('cover_image')->nullable()->comment('Image de couverture du projet');
            $table->json('gallery')->nullable()->comment('Galerie d\'images (JSON, chemins)');
            $table->boolean('is_featured')->default(false)->index()->comment('Projet mis en avant sur la vitrine');
            $table->boolean('is_published')->default(true)->index()->comment('Projet publié / visible');
            $table->timestamp('published_at')->nullable()->index()->comment('Date de publication');
            $table->timestamps();

            $table->index(['is_published', 'is_featured']);
            $table->index(['category', 'is_published']);
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete()->comment('Auteur de l\'article (FK users)');
            $table->string('slug')->unique()->comment('Identifiant URL unique de l\'article');
            $table->string('title')->comment('Titre de l\'article');
            $table->string('category')->nullable()->index()->comment('Catégorie de l\'article');
            $table->text('excerpt')->nullable()->comment('Extrait / résumé de l\'article');
            $table->longText('content')->nullable()->comment('Contenu complet de l\'article (HTML/Markdown)');
            $table->string('cover_image')->nullable()->comment('Image de couverture de l\'article');
            $table->timestamp('published_at')->nullable()->index()->comment('Date de publication');
            $table->boolean('is_published')->default(true)->index()->comment('Article publié / visible');
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('author_name')->comment('Nom de l\'auteur du témoignage');
            $table->string('company')->nullable()->comment('Entreprise de l\'auteur');
            $table->string('role')->nullable()->comment('Fonction / rôle de l\'auteur');
            $table->text('content')->comment('Contenu du témoignage');
            $table->unsignedTinyInteger('rating')->default(5)->comment('Note attribuée (1 à 5)');
            $table->string('avatar_url')->nullable()->comment('URL de l\'avatar de l\'auteur');
            $table->integer('order')->default(0)->index()->comment('Ordre d\'affichage');
            $table->boolean('is_active')->default(true)->index()->comment('Témoignage actif / visible');
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('name')->comment('Nom du partenaire');
            $table->string('logo_url')->nullable()->comment('URL du logo du partenaire');
            $table->string('website_url')->nullable()->comment('URL du site web du partenaire');
            $table->integer('order')->default(0)->index()->comment('Ordre d\'affichage');
            $table->boolean('is_active')->default(true)->index()->comment('Partenaire actif / visible');
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('key')->unique()->comment('Clé unique du paramètre (ex: site.name)');
            $table->text('value')->nullable()->comment('Valeur du paramètre (texte, JSON ou HTML)');
            $table->string('group')->default('general')->index()->comment('Groupe du paramètre (general, contact, seo, etc.)');
            $table->timestamps();

            $table->index(['group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('public_projects');
        Schema::dropIfExists('services');
        Schema::dropIfExists('expertises');
    }
};
