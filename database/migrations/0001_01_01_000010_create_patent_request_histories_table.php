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
        Schema::create('patent_request_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patent_request_id')->constrained('patent_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('action', [
                'created',
                'approved',
                'rejected',
                'rejected_with_observations'
            ]); // acción realizada
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('patent_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patent_request_history');
    }
};
