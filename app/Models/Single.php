<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Single extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'name',
        'set_name',
        'card_number',
        'rarity',
        'buy_price_cash',
        'buy_price_credit',
        'image_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'buy_price_cash'   => 'decimal:2',
            'buy_price_credit' => 'decimal:2',
            'is_active'        => 'boolean',
        ];
    }

    // --- Relaciones ---

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // --- Helpers ---

    /**
     * Precio en efectivo formateado en español.
     */
    public function precioCashFormateado(): string
    {
        return number_format($this->buy_price_cash, 2, ',', '.') . ' €';
    }

    /**
     * Precio en saldo de tienda formateado en español.
     */
    public function precioCreditFormateado(): string
    {
        return number_format($this->buy_price_credit, 2, ',', '.') . ' €';
    }
}
