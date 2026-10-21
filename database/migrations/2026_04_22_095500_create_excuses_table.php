<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El estudiante que sube el justificante
            $table->string('reason'); // Motivo de la falta
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->string('file_path')->nullable(); // Ruta del archivo/foto (justificante médico, etc.)
            $table->date('date'); // Fecha de la falta a justificar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excuses');
    }
};
