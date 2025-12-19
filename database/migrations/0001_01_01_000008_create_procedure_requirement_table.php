<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedure_requirement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id')->constrained('procedures')->cascadeOnDelete();
            $table->foreignId('requirement_id')->constrained('requirements')->cascadeOnDelete();
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
            $table->unique(['procedure_id', 'requirement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedure_requirement');
    }
};
