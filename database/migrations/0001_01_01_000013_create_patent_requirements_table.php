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
        Schema::create('patent_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // código único interno (ej: CERT_UBICACION)
            $table->string('name'); // nombre del documento
            $table->text('description')->nullable(); // descripción detallada
            $table->enum('category', ['municipal', 'sanitario', 'legal', 'profesional', 'financiero', 'transporte', 'educacion', 'seguridad', 'otros'])->default('otros');
            $table->string('where_to_obtain'); // dónde obtener el documento
            $table->string('obtain_address')->nullable(); // dirección específica
            $table->string('obtain_phone')->nullable(); // teléfono de contacto
            $table->string('info_url')->nullable(); // URL con más información
            $table->integer('validity_days')->nullable(); // días de vigencia del documento
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'category'], 'requirements_category_index');
            $table->index('code', 'requirements_code_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patent_requirements');
    }
};
