<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['expertises', 'services', 'public_projects', 'posts', 'programs'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'meta_title')) {
                    $table->string('meta_title')->nullable()->after('slug')->comment('Titre SEO (balise meta title)');
                }
                if (! Schema::hasColumn($tableName, 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_title')->comment('Description SEO (meta description)');
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['expertises', 'services', 'public_projects', 'posts', 'programs'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'meta_title')) {
                    $table->dropColumn('meta_title');
                }
                if (Schema::hasColumn($tableName, 'meta_description')) {
                    $table->dropColumn('meta_description');
                }
            });
        }
    }
};
