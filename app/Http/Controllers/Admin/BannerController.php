<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controlador: Gestión de Banners del Hero Slider
 *
 * Permite al administrador crear, editar, eliminar y activar/desactivar
 * los slides del carrusel principal de la portada sin tocar código.
 */
class BannerController extends Controller
{
    /**
     * Lista todos los banners ordenados por posición.
     */
    public function index()
    {
        // Cargamos todos los banners (activos e inactivos) para poder gestionarlos
        $banners = Banner::ordenados()->get();

        Log::info('Admin accede a la gestión de banners', [
            'usuario_id' => auth()->id(),
            'total'      => $banners->count(),
        ]);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Muestra el formulario de creación de un nuevo banner.
     */
    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner()]);
    }

    /**
     * Guarda un nuevo banner en la base de datos.
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'title'       => 'required|string|max:200',
            'subtitle'    => 'nullable|string|max:300',
            'link_url'    => 'nullable|url|max:500',
            'button_text' => 'required|string|max:50',
            'order'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
            // La imagen es obligatoria al crear
            'imagen'      => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        try {
            // Subir la imagen antes de crear el registro
            $rutaImagen = $this->subirImagen($request);

            $banner = Banner::create([
                'title'       => $datosValidados['title'],
                'subtitle'    => $datosValidados['subtitle'] ?? null,
                'image_path'  => $rutaImagen,
                'link_url'    => $datosValidados['link_url'] ?? null,
                'button_text' => $datosValidados['button_text'],
                'order'       => $datosValidados['order'],
                'is_active'   => $request->boolean('is_active'),
            ]);

            Log::info('Banner creado correctamente', [
                'banner_id'  => $banner->id,
                'titulo'     => $banner->title,
                'usuario_id' => auth()->id(),
            ]);

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner creado correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al crear banner', [
                'error'      => $e->getMessage(),
                'usuario_id' => auth()->id(),
            ]);

            return back()->withInput()
                ->with('error', 'Error al guardar el banner: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de edición de un banner existente.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    /**
     * Actualiza un banner existente.
     */
    public function update(Request $request, Banner $banner)
    {
        $datosValidados = $request->validate([
            'title'       => 'required|string|max:200',
            'subtitle'    => 'nullable|string|max:300',
            'link_url'    => 'nullable|url|max:500',
            'button_text' => 'required|string|max:50',
            'order'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
            // La imagen es opcional en edición (se mantiene la actual si no se sube)
            'imagen'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        try {
            $datosActualizar = [
                'title'       => $datosValidados['title'],
                'subtitle'    => $datosValidados['subtitle'] ?? null,
                'link_url'    => $datosValidados['link_url'] ?? null,
                'button_text' => $datosValidados['button_text'],
                'order'       => $datosValidados['order'],
                'is_active'   => $request->boolean('is_active'),
            ];

            // Solo reemplazamos la imagen si se subió una nueva
            if ($request->hasFile('imagen')) {
                $this->eliminarImagenAnterior($banner->image_path);
                $datosActualizar['image_path'] = $this->subirImagen($request);
            }

            $banner->update($datosActualizar);

            Log::info('Banner actualizado correctamente', [
                'banner_id'  => $banner->id,
                'titulo'     => $banner->title,
                'usuario_id' => auth()->id(),
            ]);

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner actualizado correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al actualizar banner', [
                'banner_id'  => $banner->id,
                'error'      => $e->getMessage(),
                'usuario_id' => auth()->id(),
            ]);

            return back()->withInput()
                ->with('error', 'Error al actualizar el banner: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un banner y su imagen del servidor.
     */
    public function destroy(Banner $banner)
    {
        try {
            $tituloBanner = $banner->title;

            // Eliminar el archivo de imagen antes de borrar el registro
            $this->eliminarImagenAnterior($banner->image_path);

            $banner->delete();

            Log::info('Banner eliminado', [
                'titulo'     => $tituloBanner,
                'usuario_id' => auth()->id(),
            ]);

            return redirect()->route('admin.banners.index')
                ->with('success', "Banner \"{$tituloBanner}\" eliminado correctamente.");

        } catch (\Throwable $e) {
            Log::error('Error al eliminar banner', [
                'banner_id'  => $banner->id,
                'error'      => $e->getMessage(),
                'usuario_id' => auth()->id(),
            ]);

            return back()->with('error', 'Error al eliminar el banner.');
        }
    }

    /**
     * Activa o desactiva un banner mediante petición AJAX (toggle).
     * Devuelve JSON para actualizar la UI sin recargar la página.
     */
    public function toggleActivo(Banner $banner)
    {
        try {
            $banner->update(['is_active' => !$banner->is_active]);

            Log::info('Estado de banner cambiado', [
                'banner_id'  => $banner->id,
                'activo'     => $banner->is_active,
                'usuario_id' => auth()->id(),
            ]);

            return response()->json([
                'success'   => true,
                'is_active' => $banner->is_active,
                'mensaje'   => $banner->is_active ? 'Banner activado' : 'Banner desactivado',
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al cambiar estado del banner', ['error' => $e->getMessage()]);

            return response()->json(['success' => false], 500);
        }
    }

    // ── Métodos privados de ayuda ─────────────────────────────────────────

    /**
     * Sube la imagen del banner a public/images/banners/ y devuelve la ruta relativa.
     */
    private function subirImagen(Request $request): string
    {
        $archivo      = $request->file('imagen');
        $nombreUnico  = time() . '_' . preg_replace('/[^a-z0-9._-]/i', '_', $archivo->getClientOriginalName());
        $carpetaDestino = public_path('images/banners');

        // Crear la carpeta si no existe
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        $archivo->move($carpetaDestino, $nombreUnico);

        return 'images/banners/' . $nombreUnico;
    }

    /**
     * Elimina el archivo de imagen anterior del servidor (si existe y no es placeholder).
     */
    private function eliminarImagenAnterior(?string $rutaImagen): void
    {
        if ($rutaImagen && file_exists(public_path($rutaImagen))) {
            unlink(public_path($rutaImagen));
        }
    }
}
