<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Lista todos los eventos ordenados por fecha de inicio.
     */
    public function index()
    {
        $events = Event::orderBy('start_date')->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Muestra el formulario de creación de evento.
     */
    public function create()
    {
        return view('admin.events.form');
    }

    /**
     * Almacena un nuevo evento en la base de datos.
     */
    public function store(Request $request)
    {
        $data = $this->validarEvento($request);

        // Validación y subida de imagen de cartel (sin MIME, igual que BannerController)
        if ($request->hasFile('image')) {
            $errorExt = $this->validarExtensionImagen($request->file('image'));
            if ($errorExt) {
                return back()->withInput()->withErrors(['image' => $errorExt]);
            }
            $data['image_path'] = $this->subirImagen($request);
        }

        Event::create($data);

        Log::info('Evento creado', ['titulo' => $data['title']]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento creado correctamente.');
    }

    /**
     * Muestra el formulario de edición de un evento existente.
     * Parámetro $evento coincide con {evento} de la ruta resource.
     */
    public function edit(Event $evento)
    {
        return view('admin.events.form', ['event' => $evento]);
    }

    /**
     * Actualiza los datos de un evento existente.
     */
    public function update(Request $request, Event $evento)
    {
        $data = $this->validarEvento($request);

        if ($request->hasFile('image')) {
            $errorExt = $this->validarExtensionImagen($request->file('image'));
            if ($errorExt) {
                return back()->withInput()->withErrors(['image' => $errorExt]);
            }
            // Eliminar cartel anterior si existe
            $this->eliminarImagenAnterior($evento->image_path);
            $data['image_path'] = $this->subirImagen($request);
        }

        $evento->update($data);

        Log::info('Evento actualizado', ['id' => $evento->id, 'titulo' => $evento->title]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Elimina un evento de la base de datos (y su cartel si existe).
     */
    public function destroy(Event $evento)
    {
        $this->eliminarImagenAnterior($evento->image_path);
        $evento->delete();

        Log::info('Evento eliminado', ['id' => $evento->id, 'titulo' => $evento->title]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento eliminado correctamente.');
    }

    // ── Helpers privados ────────────────────────────────────────────────────

    /**
     * Valida los campos del formulario de evento.
     * No usa validación MIME (fileinfo bloqueado por Windows App Control).
     */
    private function validarEvento(Request $request): array
    {
        return $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'price'           => 'nullable|numeric|min:0',
            'google_maps_url' => 'nullable|url|max:500',
            'is_active'       => 'boolean',
            // Sin 'image' ni 'mimes': la extensión se valida manualmente
            'image'           => 'nullable|max:6144',
        ]);
    }

    /**
     * Valida la extensión del archivo de imagen sin usar fileinfo/MIME detection.
     * Extensiones aceptadas: jpg, jpeg, png, webp.
     *
     * @return string|null Mensaje de error, o null si es válida.
     */
    private function validarExtensionImagen($archivo): ?string
    {
        if (!$archivo || !$archivo->isValid()) {
            return 'El archivo no es válido o hubo un error al subirlo.';
        }

        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower($archivo->getClientOriginalExtension());

        if (!in_array($extension, $extensionesPermitidas, true)) {
            return 'Solo se aceptan imágenes JPG, JPEG, PNG o WebP. '
                . "Se recibió: \".{$extension}\"";
        }

        return null;
    }

    /**
     * Sube el cartel del evento a public/images/events/.
     * Limpia el nombre para evitar dobles extensiones y caracteres especiales.
     */
    private function subirImagen(Request $request): string
    {
        $archivo   = $request->file('image');
        $extension = strtolower($archivo->getClientOriginalExtension());

        // Limpiar el stem del nombre: eliminar extensión anidada (ej: foto.jpg.webp → foto)
        $stemOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $stemLimpio   = preg_replace('/\.[a-z0-9]+$/i', '', $stemOriginal);
        $stemSeguro   = Str::slug($stemLimpio) ?: 'evento';

        $nombreFinal = time() . '_' . $stemSeguro . '.' . $extension;

        $carpeta = public_path('images/events');
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $archivo->move($carpeta, $nombreFinal);

        Log::info('Cartel de evento subido', ['archivo' => $nombreFinal]);

        return 'images/events/' . $nombreFinal;
    }

    /**
     * Elimina el archivo de imagen anterior del disco si existe.
     */
    private function eliminarImagenAnterior(?string $imagePath): void
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
    }
}
