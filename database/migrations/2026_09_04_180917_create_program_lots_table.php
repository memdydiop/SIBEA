<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_lots', function (Blueprint $table) {
            $table->id()->comment('Identifiant unique');
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete()->comment('Lotissement parent');
            $table->string('reference')->unique()->comment('Référence unique du lot ex: Lot 0001');
            $table->decimal('surface', 10, 2)->nullable()->comment('Surface du lot en m²');
            $table->decimal('price', 12, 2)->nullable()->comment('Prix en FCFA');
            $table->string('status')->index()->comment('Statut commercial: disponible, option, reserve, vendu (LotStatus)');
            $table->boolean('is_viabilise')->default(true)->comment('Lot viabilisé (voirie, eau, élec.)');
            $table->string('juridical_status')->nullable()->comment('Statut juridique / ACD ex: ACD, TF');
            $table->string('plan_pdf_path')->nullable()->comment('Chemin PDF du plan de lot');
            $table->decimal('latitude', 10, 7)->nullable()->comment('Latitude GPS');
            $table->decimal('longitude', 10, 7)->nullable()->comment('Longitude GPS');
            $table->timestamp('published_at')->nullable()->comment('Date de publication');
            $table->timestamps();
            $table->softDeletes()->comment('Suppression douce');

            $table->index(['program_id', 'status']);
            $table->index(['status', 'is_viabilise']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_lots');
    }
};
