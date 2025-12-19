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
        Schema::create('patent_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código de seguimiento: SOL_001_2025
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('rut'); // RUT del contribuyente
            $table->string('business_address'); // dirección del negocio
            $table->string('business_activity'); // giro o actividad comercial
            $table->enum('state', [
                'pending', // cuando se crea la solicitud
                'approved', // cuando se aprueba la solicitud
                'rejected', // cuando se rechaza la solicitud
                'rejected_with_observations' // cuando se rechaza la solicitud con observaciones
            ])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); // funcionario que revisa
            $table->timestamp('reviewed_at')->nullable(); // fecha de revisión
            $table->text('observations')->nullable(); // observaciones del funcionario
            $table->timestamps();
            $table->softDeletes();

            $table->index('state', 'state_index');
            $table->index('rut', 'rut_index');
            $table->index(['user_id', 'state'], 'user_state_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patent_requests');
    }
};
