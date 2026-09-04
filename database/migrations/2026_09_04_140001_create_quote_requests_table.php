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
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company')->nullable();
            $table->string('role')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('location')->nullable();
            $table->string('service_type');
            $table->string('project_nature')->nullable();
            $table->string('estimated_budget')->nullable();
            $table->string('desired_timeline')->nullable();
            $table->text('description');
            $table->string('status')->default('nouveau');
            $table->boolean('consent')->default(true);
            $table->text('internal_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
