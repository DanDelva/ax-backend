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
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained(); // Quién paga
                $table->decimal('amount', 10, 2); // Monto del pago
                $table->string('concept'); // RF2.1: Colegiatura, Inscripción, etc. [cite: 41]
                // RF2.2: Estatus para validación de pagos [cite: 44]
                $table->enum('status', ['pendiente', 'validado', 'rechazado'])->default('pendiente');
                $table->date('payment_date');
                $table->string('proof_path')->nullable(); // Para subir foto del comprobante (Seguridad RNF2) [cite: 52]
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
