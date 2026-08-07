<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'guest_email',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_postal_code', 'shipping_country',
        'subtotal', 'shipping_cost', 'discount', 'total',
        'status', 'payment_method', 'payment_intent_id', 'payment_order_id',
        'paid_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'      => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount'      => 'decimal:2',
            'total'         => 'decimal:2',
            'paid_at'       => 'datetime',
        ];
    }

    // --- Boot: generar número de pedido automáticamente ---

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->order_number = static::generateOrderNumber();
        });
    }

    protected static function generateOrderNumber(): string
    {
        $year  = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'FC-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    // --- Relaciones ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // --- Helpers ---

    public function isPaid(): bool
    {
        return !is_null($this->paid_at);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'    => 'Pendiente',
            'paid'       => 'Pagado',
            'processing' => 'En preparación',
            'shipped'    => 'Enviado',
            'delivered'  => 'Entregado',
            'cancelled'  => 'Cancelado',
            'refunded'   => 'Reembolsado',
            default      => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'    => 'warning',
            'paid'       => 'info',
            'processing' => 'primary',
            'shipped'    => 'info',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            'refunded'   => 'secondary',
            default      => 'secondary',
        };
    }
}
