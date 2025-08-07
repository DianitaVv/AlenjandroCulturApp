<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin')->nullable();
            $table->string('ubicacion');
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->string('imagen')->nullable();
            $table->string('contacto')->nullable();
            $table->string('telefono')->nullable();
            $table->decimal('precio', 8, 2)->default(0);
            $table->foreignId('user_id')->constrained();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos');
    }
};