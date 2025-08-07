<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sitio_culturals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->text('historia')->nullable();
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->string('direccion');
            $table->string('imagen')->nullable();
            $table->json('galeria_imagenes')->nullable();
            $table->string('tipo'); // museo, iglesia, plaza, monumento, etc.
            $table->string('horarios')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sitio_culturals');
    }
};