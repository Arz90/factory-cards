<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Controlador: Gestión del Promo Banner de la portada.
 *
 * Permite al administrador crear, editar, eliminar y activar/desactivar
 * la sección destacada split 50/50 de la portada sin tocar código.
 * Solo un banner puede estar activo a la vez.
 */
class PromoBannerController extends Controller
{
    public function index()
    {
        $banners = PromoBanner::orderByDesc('is_active')->orderByDesc('updated_at')->get();

        return view('admin.promo_banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.promo_banners.form', ['banner' => new PromoBanner()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:200',
            'franchise_label' => 'nullable|string|max:100',
            'launch_date'     => 'nullable|date',
            'description'     => 'required|string|max:1000',
            'button_text'     => 'required|string|max:60',
            'button_url'      => 'required|string|max:500',
            // Sin 'image'/'mimes' — fileinfo bloqueada por Windows App Control.
            'imagen'          => 'nullable|max:4096',
        ]);

        if ($request->hasFile('imagen')) {
            $error = $this->validarExtension($request->file('imagen'));
            if ($error) {
                return back()->withInput()->withErrors(['imagen' => $error]);
            }
        }

        try {
            // Al activar este banner, desactivar todos los demás
            if ($request->boolean('is_active')) {
                PromoBanner::query()->update(['is_active' => false]);
            }

            $datos = [
                'title'           => $request->title,
                'franchise_label' => $request->franchise_label ?? '',
                'launch_date'     => $request->launch_date ?: null,
                'description'     => $request->description,
                'button_text'     => $request->button_text,
                'button_url'      => $request->button_url,
                'is_active'       => $request->boolean('is_active'),
            ];

            if ($request->hasFile('imagen')) {
                $datos['image_path'] = $this->subirImagen($request);
            }

            $banner = PromoBanner::create($datos);

            Log::info('Promo banner creado', ['id' => $banner->id, 'usuario_id' => auth()->id()]);

            return redirect()->route('admin.promo-banners.index')
                ->with('success', 'Promo banner creado correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al crear promo banner', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function edit(PromoBanner $promoBanner)
    {
        return view('admin.promo_banners.form', ['banner' => $promoBanner]);
    }

    public function update(Request $request, PromoBanner $promoBanner)
    {
        $request->validate([
            'title'           => 'required|string|max:200',
            'franchise_label' => 'nullable|string|max:100',
            'launch_date'     => 'nullable|date',
            'description'     => 'required|string|max:1000',
            'button_text'     => 'required|string|max:60',
            'button_url'      => 'required|string|max:500',
            'imagen'          => 'nullable|max:4096',
        ]);

        if ($request->hasFile('imagen')) {
            $error = $this->validarExtension($request->file('imagen'));
            if ($error) {
                return back()->withInput()->withErrors(['imagen' => $error]);
            }
        }

        try {
            if ($request->boolean('is_active')) {
                PromoBanner::where('id', '!=', $promoBanner->id)->update(['is_active' => false]);
            }

            $datos = [
                'title'           => $request->title,
                'franchise_label' => $request->franchise_label ?? '',
                'launch_date'     => $request->launch_date ?: null,
                'description'     => $request->description,
                'button_text'     => $request->button_text,
                'button_url'      => $request->button_url,
                'is_active'       => $request->boolean('is_active'),
            ];

            if ($request->hasFile('imagen')) {
                $this->eliminarImagen($promoBanner->image_path);
                $datos['image_path'] = $this->subirImagen($request);
            }

            $promoBanner->update($datos);

            Log::info('Promo banner actualizado', ['id' => $promoBanner->id, 'usuario_id' => auth()->id()]);

            return redirect()->route('admin.promo-banners.index')
                ->with('success', 'Promo banner actualizado correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al actualizar promo banner', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy(PromoBanner $promoBanner)
    {
        try {
            $this->eliminarImagen($promoBanner->image_path);
            $promoBanner->delete();

            Log::info('Promo banner eliminado', ['id' => $promoBanner->id, 'usuario_id' => auth()->id()]);

            return redirect()->route('admin.promo-banners.index')
                ->with('success', 'Promo banner eliminado correctamente.');

        } catch (\Throwable $e) {
            Log::error('Error al eliminar promo banner', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al eliminar el promo banner.');
        }
    }

    /**
     * Toggle activo/inactivo. Al activar, desactiva los demás (solo uno activo).
     */
    public function toggleActivo(PromoBanner $promoBanner)
    {
        try {
            $nuevoEstado = !$promoBanner->is_active;

            if ($nuevoEstado) {
                PromoBanner::where('id', '!=', $promoBanner->id)->update(['is_active' => false]);
            }

            $promoBanner->update(['is_active' => $nuevoEstado]);

            return response()->json([
                'ok'        => true,
                'is_active' => $nuevoEstado,
                'mensaje'   => $nuevoEstado ? 'Promo banner activado' : 'Promo banner desactivado',
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al cambiar estado del promo banner', ['error' => $e->getMessage()]);
            return response()->json(['ok' => false], 500);
        }
    }

    // ── Helpers privados ──────────────────────────────────────────────────

    private function validarExtension($archivo): ?string
    {
        if (!$archivo || !$archivo->isValid()) {
            return 'El archivo no es válido o hubo un error al subirlo.';
        }

        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower($archivo->getClientOriginalExtension());

        if (!in_array($ext, $permitidas, true)) {
            return "Solo se aceptan JPG, JPEG, PNG o WebP. Se recibió: \".{$ext}\"";
        }

        return null;
    }

    private function subirImagen(Request $request): string
    {
        $archivo   = $request->file('imagen');
        $ext       = strtolower($archivo->getClientOriginalExtension());
        $stem      = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $stemLimpio = preg_replace('/\.[a-z0-9]+$/i', '', $stem);
        $nombre    = time() . '_' . (Str::slug($stemLimpio) ?: 'promo') . '.' . $ext;
        $carpeta   = public_path('images/promo_banners');

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $archivo->move($carpeta, $nombre);

        return 'images/promo_banners/' . $nombre;
    }

    private function eliminarImagen(?string $ruta): void
    {
        if ($ruta && file_exists(public_path($ruta))) {
            unlink(public_path($ruta));
        }
    }
}
