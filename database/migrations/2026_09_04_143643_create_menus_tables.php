<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->string('name')->comment('Nom du menu (ex: Menu principal)');
            $table->string('slug')->unique()->comment('Identifiant unique du menu');
            $table->string('location')->default('header')->index()->comment('Emplacement du menu (header, footer, sidebar)');
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id()->comment('Identifiant primaire');
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete()->comment('Menu parent (FK menus)');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete()->comment('Élément parent (hiérarchie, FK menu_items)');
            $table->string('label')->comment('Libellé affiché de l\'élément de menu');
            $table->string('url')->comment('URL / lien de l\'élément');
            $table->string('target')->default('_self')->comment('Cible du lien (_self, _blank)');
            $table->integer('order')->default(0)->index()->comment('Ordre d\'affichage dans le menu');
            $table->boolean('is_active')->default(true)->index()->comment('Élément actif / visible');
            $table->timestamps();

            $table->index(['menu_id', 'is_active', 'order']);
            $table->index(['parent_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
};
