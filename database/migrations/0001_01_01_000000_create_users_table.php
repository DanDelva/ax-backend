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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre completo del estudiante/docente [cite: 55]
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // --- NUEVOS CAMPOS PARA PROYECTO AX ---
            // Roles: 'admin' (CTO/Op), 'docente', 'estudiante' [cite: 56, 57]
            $table->enum('role', ['admin', 'docente', 'estudiante'])->default('estudiante');
            $table->string('phone')->nullable(); 
            $table->string('matricula')->nullable()->unique(); // Para AL01, PR01, AD01
            // --------------------------------------

            $table->rememberToken();
            $table->timestamps();
        });

        // Estos los dejamos igual, son necesarios para Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
