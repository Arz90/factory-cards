<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Wishlist — representa un producto guardado en favoritos por un usuario.
 */
class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    // --- Relaciones ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
