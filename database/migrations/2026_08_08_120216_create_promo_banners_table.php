<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_banners', function (Blueprint $table) {
            $table->id();
            $table->string('franchise_label')->default('');   // ej: "MAGIC: THE GATHERING"
            $table->string('title');                          // ej: "SECRETOS DE STRIXHAVEN"
            $table->date('launch_date')->nullable();          // fecha de lanzamiento
            $table->text('description');                      // texto descriptivo
            $table->string('button_text')->default('VER PRODUCTO');
            $table->string('button_url')->default('/tienda'); // ruta o URL del botón
            $table->string('image_path')->nullable();         // ruta relativa en public/
            $table->boolean('is_active')->default(false);     // solo uno activo a la vez
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_banners');
    }
};
