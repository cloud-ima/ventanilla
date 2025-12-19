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
        Schema::create('patent_request_requirements', function (Blueprint $table) {
            // Clave primaria compuesta para tabla pivote
            $table->foreignId('patent_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patent_requirement_id')->constrained()->cascadeOnDelete();
            
            // Definir clave primaria compuesta
            $table->primary(['patent_request_id', 'patent_requirement_id'], 'pk_request_requirement');
            
            $table->text('observations')->nullable(); // observaciones sobre el documento ( no es un archivo adjunto )
            $table->foreignId('attached_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patent_request_requirements');
    }
};
