<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->decimal('precio_unitario', 10, 2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};
