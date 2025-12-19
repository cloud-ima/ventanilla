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
        Schema::create('patent_request_forms', function (Blueprint $table) {
            // Clave primaria compuesta para tabla pivote
            $table->foreignId('patent_request_id')->constrained('patent_requests')->cascadeOnDelete();
            $table->foreignId('patent_form_id')->constrained('patent_forms')->cascadeOnDelete();
            
            // Definir clave primaria compuesta
            $table->primary(['patent_request_id', 'patent_form_id'], 'pk_request_form');
            
            $table->foreignId('attached_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patent_request_forms');
    }
};
