<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'price',
        'image_path',
        'google_maps_url',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'price'      => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────

    /** Devuelve solo los eventos activos. */
    public function scopeActivos($query)
    {
        return $query->where('is_active', true);
    }

    /** Ordena por fecha de inicio ascendente. */
    public function scopeProximos($query)
    {
        return $query->orderBy('start_date');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Devuelve la URL pública del cartel del evento.
     * Si no tiene imagen subida, devuelve un placeholder.
     */
    public function urlImagen(): string
    {
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }

        return 'https://placehold.co/800x450/1a2332/ffffff?text=' . urlencode($this->title);
    }

    /**
     * Indica si el evento tiene precio de inscripción.
     */
    public function esPago(): bool
    {
        return $this->price !== null && $this->price > 0;
    }

    /**
     * Devuelve el precio formateado en euros, o "Gratuito" si es null/0.
     */
    public function precioFormateado(): string
    {
        if (!$this->esPago()) {
            return 'Gratuito';
        }

        return number_format($this->price, 2, ',', '.') . ' €';
    }
}
