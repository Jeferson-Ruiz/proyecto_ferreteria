<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes_mayoristas', function (Blueprint $table) {
            $table->id();
            $table->string('empresa');
            $table->string('contacto');
            $table->string('telefono');
            $table->string('correo');
            $table->text('direccion');
            $table->decimal('debe', 10, 2)->default(0);
            // Sin timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes_mayoristas');
    }
};
