<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Tabla de banners para el hero slider de la portada.
 * Los banners se gestionan desde el panel admin sin tocar código.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            // Título visible superpuesto sobre el banner
            $table->string('title');

            // Subtítulo descriptivo opcional
            $table->string('subtitle')->nullable();

            // Ruta de la imagen relativa a /public (ej: images/banners/foto.jpg)
            $table->string('image_path');

            // URL de destino al hacer clic — null si no enlaza
            $table->string('link_url')->nullable();

            // Texto del botón de llamada a la acción
            $table->string('button_text')->default('VER MÁS');

            // Controla si aparece en la portada
            $table->boolean('is_active')->default(true);

            // Posición en el slider (menor número = primero)
            $table->unsignedSmallInteger('order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
