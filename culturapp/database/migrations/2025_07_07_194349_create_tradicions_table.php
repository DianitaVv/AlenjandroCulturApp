<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tradicions', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->text('historia')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->json('galeria_imagenes')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('ubicacion')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->date('fecha_celebracion')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('user_id')->constrained();
            $table->boolean('activo')->default(true);
            $table->integer('likes')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tradicions');
    }
};