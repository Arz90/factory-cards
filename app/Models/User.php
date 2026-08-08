<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'phone', 'address', 'city', 'postal_code', 'country',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Helpers de Rol ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // --- Relaciones ---

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /** Registros de la tabla wishlists de este usuario */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Productos en la lista de deseos del usuario.
     * Permite usar eager loading y la query builder de Product directamente.
     */
    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps();
    }

    // Productos comprados previamente (para recomendaciones)
    public function purchasedProducts()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            Order::class,
            'user_id',
            'order_id'
        );
    }
}
