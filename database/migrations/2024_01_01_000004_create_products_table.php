<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('franchise_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);                      // Precio de venta actual
            $table->decimal('original_price', 10, 2)->nullable(); // Precio antes del descuento
            $table->decimal('cost_price', 10, 2)->nullable();     // Precio de coste (solo admin)
            $table->integer('stock')->default(0);
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->nullable();
            $table->string('image_url')->nullable();              // Imagen principal
            $table->json('gallery')->nullable();                   // Galería adicional de imágenes
            $table->enum('status', ['active', 'inactive', 'preorder'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->decimal('weight', 8, 2)->nullable();          // Para cálculo de envío (kg)
            $table->json('attributes')->nullable();               // Atributos extra (idioma, edición, etc.)
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured']);
            $table->index(['category_id', 'franchise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
