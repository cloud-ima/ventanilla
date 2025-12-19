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
        Schema::create('patent_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('template_file'); // archivo de plantilla
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // usuario que subió el formulario
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'is_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patent_forms');
    }
};
