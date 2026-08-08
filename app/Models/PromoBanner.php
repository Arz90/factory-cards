<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $fillable = [
        'franchise_label',
        'title',
        'launch_date',
        'description',
        'button_text',
        'button_url',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'launch_date' => 'date',
        'is_active'   => 'boolean',
    ];

    /**
     * Devuelve el promo banner activo (el más reciente si hubiera varios).
     */
    public static function activo(): ?self
    {
        return static::where('is_active', true)->latest('updated_at')->first();
    }

    /**
     * URL pública de la imagen, o null si no tiene.
     */
    public function urlImagen(): ?string
    {
        return $this->image_path ? asset($this->image_path) : null;
    }

    /**
     * Fecha de lanzamiento formateada en español (ej: "15 de noviembre de 2025").
     */
    public function fechaFormateada(): ?string
    {
        if (!$this->launch_date) {
            return null;
        }

        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
        ];

        return $this->launch_date->day
            . ' de ' . $meses[$this->launch_date->month]
            . ' de ' . $this->launch_date->year;
    }
}
