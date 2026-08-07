<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Banner — representa un slide del hero carousel de la portada.
 *
 * Scopes disponibles:
 *   Banner::activos()   → solo banners con is_active = true
 *   Banner::ordenados() → ordenados por 'order' ASC, luego por id ASC
 */
class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'link_url',
        'button_text',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────

    /**
     * Filtra solo los banners marcados como activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ordena por la columna 'order' ascendente (menor número = primer slide).
     */
    public function scopeOrdenados($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Devuelve la URL pública de la imagen del banner.
     * Usa un placeholder si no hay imagen asignada.
     */
    public function urlImagen(): string
    {
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }

        // Placeholder generado con placehold.co para entornos de desarrollo
        return 'https://placehold.co/1600x520/1a2332/ffffff?text=' . urlencode($this->title);
    }
}
