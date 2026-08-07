<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // "Pokémon", "Magic: The Gathering", etc.
            $table->string('slug')->unique();
            $table->string('icon_url')->nullable(); // Ruta al icono/logo de la franquicia
            $table->string('color', 7)->nullable(); // Color hex para highlight (#FFCB05 para Pokémon)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchises');
    }
};
