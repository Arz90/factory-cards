<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'franchise_id', 'name', 'slug', 'description',
        'short_description', 'price', 'original_price', 'cost_price',
        'stock', 'sku', 'barcode', 'image_url', 'gallery',
        'status', 'is_featured', 'weight', 'attributes',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'decimal:2',
            'original_price' => 'decimal:2',
            'cost_price'     => 'decimal:2',
            'is_featured'    => 'boolean',
            'gallery'        => 'array',
            'attributes'     => 'array',
        ];
    }

    // --- Relaciones ---

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopePreorder($query)
    {
        return $query->where('status', 'preorder');
    }

    // --- Helpers / Accessors ---

    public function hasDiscount(): bool
    {
        return !is_null($this->original_price) && $this->original_price > $this->price;
    }

    public function discountPercentage(): int
    {
        if (!$this->hasDiscount()) return 0;
        return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    public function isAvailable(): bool
    {
        return in_array($this->status, ['active', 'preorder']) && $this->stock > 0;
    }

    public function badgeLabel(): ?string
    {
        if ($this->status === 'preorder') return 'PRECOMPRA';
        if ($this->hasDiscount()) return 'OFERTA';
        return null;
    }

    public function badgeColor(): string
    {
        return match ($this->status) {
            'preorder' => 'primary',
            default    => 'danger',
        };
    }
}
