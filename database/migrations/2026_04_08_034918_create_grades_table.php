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
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El estudiante
                $table->foreignId('subject_id')->constrained(); // La materia
                $table->decimal('score', 5, 2); // Calificación (ej: 9.50)
                // Diferenciación requerida por el RF1.1 
                $table->enum('type', ['teorica', 'practica']); 
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
