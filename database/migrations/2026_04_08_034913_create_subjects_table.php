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
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                // Relación con la especialidad (si se borra la carrera, se borran sus materias)
                $table->foreignId('specialty_id')->constrained()->onDelete('cascade');
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('title'); // Nombre de la materia
                $table->text('content_manual'); // RF1.3: Contenido de manuales 
                $table->text('material_list'); // RF1.3: Listas de materiales necesarios 
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
