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
        Schema::create('singles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->restrictOnDelete();
            $table->string('name');                        // Ej: "Charizard ex"
            $table->string('set_name')->nullable();        // Ej: "151"
            $table->string('card_number')->nullable();     // Ej: "199/165"
            $table->string('rarity')->nullable();          // Ej: "Ultra Rare"
            $table->decimal('buy_price_cash', 8, 2);      // Precio de compra en efectivo
            $table->decimal('buy_price_credit', 8, 2);    // Precio de compra en saldo de tienda
            $table->string('image_url')->nullable();       // Imagen de la carta
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['franchise_id', 'is_active']);
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('singles');
    }
};
