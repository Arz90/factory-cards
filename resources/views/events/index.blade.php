{{--
    Vista: events/index
    Propósito: Página pública de eventos y torneos con calendario interactivo FullCalendar v6.
    Al hacer clic en un evento del calendario se abre un modal Bootstrap 5 con todos los detalles.
--}}
@extends('layouts.app')
@section('title', 'Eventos y Torneos — Factory Cards')
@section('meta_description', 'Consulta todos los torneos, presentaciones y eventos de Factory Cards. Inscríbete y vive la experiencia TCG.')

@section('content')

{{-- ── Cabecera de sección ── --}}
<div class="eventos-hero">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="fw-black mb-2" style="font-size:clamp(1.8rem,4vw,2.8rem);">
                    <i class="bi bi-calendar-event me-2" style="color:var(--fc-amarillo)"></i>
                    Eventos y Torneos
                </h1>
                <p class="text-white-50 mb-0 fs-6">
                    Participa en nuestros torneos, presentaciones y noches de juego.<br>
                    Inscríbete y vive la experiencia TCG al máximo.
                </p>
            </div>
            @if(auth()->user()?->isAdmin())
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('admin.eventos.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-pencil me-1"></i>Gestionar eventos (admin)
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        {{-- ════════════════════════
             Calendario FullCalendar
        ════════════════════════ --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div id="fullcalendar"></div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════
             Lista de próximos eventos
        ════════════════════════ --}}
        <div class="col-lg-4">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-list-ul me-2" style="color:var(--fc-verde)"></i>Próximos eventos
            </h5>

            @forelse($eventos as $evento)
                {{-- Tarjeta de evento — al clic abre el modal con los detalles --}}
                <div class="evento-card mb-3"
                     role="button"
                     tabindex="0"
                     data-id="{{ $evento->id }}"
                     data-titulo="{{ $evento->title }}"
                     data-inicio="{{ $evento->start_date->format('d/m/Y H:i') }}"
                     data-fin="{{ $evento->end_date?->format('d/m/Y H:i') }}"
                     data-precio="{{ $evento->precioFormateado() }}"
                     data-descripcion="{{ e($evento->description) }}"
                     data-imagen="{{ $evento->urlImagen() }}"
                     data-maps="{{ $evento->google_maps_url }}"
                     onclick="abrirModalEvento(this)">

                    {{-- Imagen miniatura --}}
                    <img src="{{ $evento->urlImagen() }}"
                         alt="{{ $evento->title }}"
                         class="evento-card-img">

                    {{-- Info --}}
                    <div class="evento-card-body">
                        <div class="evento-card-titulo">{{ $evento->title }}</div>
                        <div class="evento-card-fecha">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $evento->start_date->format('d M Y') }}
                            @if($evento->end_date && $evento->end_date->format('d M Y') !== $evento->start_date->format('d M Y'))
                                — {{ $evento->end_date->format('d M Y') }}
                            @endif
                        </div>
                        <span class="evento-card-precio badge {{ $evento->esPago() ? 'bg-primary' : 'bg-success' }}">
                            {{ $evento->precioFormateado() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                    No hay eventos programados próximamente.
                </div>
            @endforelse
        </div>

    </div>
</div>


{{-- ════════════════════════════════════════════════════════════
     MODAL de detalle del evento (Bootstrap 5)
     Se rellena dinámicamente en JavaScript al clicar un evento.
════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">

            {{-- Cartel del evento a ancho completo --}}
            <div id="modal-imagen-wrapper" style="max-height:320px;overflow:hidden;background:#1a2332;">
                <img id="modal-imagen"
                     src=""
                     alt="Cartel del evento"
                     style="width:100%;object-fit:cover;max-height:320px;">
            </div>

            <div class="modal-body p-4">

                {{-- Precio badge (esquina superior derecha del cuerpo) --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="modal-title fw-black mb-0" id="modalEventoLabel" style="font-size:1.4rem;line-height:1.2;"></h4>
                    <span id="modal-precio" class="badge fs-6 ms-3 flex-shrink-0"></span>
                </div>

                {{-- Fechas --}}
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <div class="modal-meta-item">
                        <i class="bi bi-calendar3 me-1" style="color:var(--fc-verde)"></i>
                        <span id="modal-inicio"></span>
                    </div>
                    <div class="modal-meta-item" id="modal-fin-wrapper">
                        <i class="bi bi-calendar3-range me-1" style="color:var(--fc-verde)"></i>
                        <span id="modal-fin"></span>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Descripción con saltos de línea --}}
                <div id="modal-descripcion"
                     class="modal-descripcion-texto"
                     style="white-space:pre-line;line-height:1.7;color:#374151;"></div>

            </div>

            <div class="modal-footer border-top d-flex justify-content-between">

                {{-- Botón Google Maps (visible solo si hay URL) --}}
                <a id="modal-maps-btn"
                   href="#"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-success d-none">
                    <i class="bi bi-geo-alt-fill me-2"></i>Cómo llegar
                </a>

                <button type="button" class="btn btn-outline-secondary ms-auto" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('styles')
{{-- FullCalendar v6 — bundle global (incluye todos los plugins y CSS) --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

<style>
/* ── Cabecera hero de eventos ── */
.eventos-hero {
    background: linear-gradient(135deg, #0f1923 0%, #1a2d3d 60%, #0f2318 100%);
    color: #fff;
    border-bottom: 2px solid var(--fc-verde);
}

/* ── Calendario FullCalendar — personalización visual ── */
#fullcalendar { min-height: 480px; }

.fc .fc-toolbar-title { font-size: 1.1rem; font-weight: 800; }
.fc .fc-button-primary {
    background: var(--fc-verde) !important;
    border-color: var(--fc-verde-dark) !important;
    font-size: .8rem !important;
}
.fc .fc-button-primary:hover,
.fc .fc-button-primary:not(:disabled):active {
    background: var(--fc-verde-dark) !important;
}
.fc .fc-daygrid-event {
    border-radius: 4px;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    padding: 2px 5px;
}
.fc .fc-day-today { background: rgba(41,164,79,.08) !important; }
.fc .fc-col-header-cell-cushion { font-weight: 700; font-size: .8rem; text-transform: uppercase; }

/* ── Tarjetas de la lista lateral ── */
.evento-card {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 12px;
    cursor: pointer;
    transition: box-shadow .18s, transform .18s;
}
.evento-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,.1);
    transform: translateY(-2px);
}
.evento-card-img {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
}
.evento-card-body   { flex: 1; min-width: 0; }
.evento-card-titulo { font-weight: 700; font-size: .9rem; margin-bottom: 4px; line-height: 1.3; }
.evento-card-fecha  { font-size: .78rem; color: #6b7280; margin-bottom: 6px; }
.evento-card-precio { font-size: .72rem; }

/* ── Modal ── */
.modal-meta-item { font-size: .88rem; color: #374151; }
.modal-descripcion-texto { font-size: .93rem; }
</style>
@endpush


@push('scripts')
{{-- FullCalendar v6 — global bundle --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
// Datos de eventos pasados desde el controlador PHP en formato JSON para FullCalendar
const eventosFC = @json($eventosCalendario);

/**
 * Inicializa el calendario FullCalendar en español con los eventos de la tienda.
 * Al hacer clic en un evento se abre el modal de detalle de Bootstrap 5.
 */
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('fullcalendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        // Idioma y formato de semana europeo (lunes como primer día)
        locale: 'es',
        firstDay: 1,
        initialView: 'dayGridMonth',
        height: 'auto',

        // Botones de la barra de herramientas
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,listMonth'
        },

        buttonText: {
            today:     'Hoy',
            month:     'Mes',
            list:      'Lista',
        },

        // Eventos cargados desde el controlador
        events: eventosFC,

        // Al clicar un evento del calendario: abrir el modal con sus datos
        eventClick: function (info) {
            const props = info.event.extendedProps;
            abrirModalConDatos({
                titulo:        info.event.title,
                inicio:        props.start_formato,
                fin:           props.end_formato,
                precio:        props.precio,
                descripcion:   props.description,
                imagen:        props.image_url,
                mapsUrl:       props.google_maps_url,
            });
        },
    });

    calendar.render();
});

/**
 * Abre el modal de detalle rellenando los campos con los datos del evento.
 * Se llama tanto desde el eventClick de FullCalendar como desde las tarjetas laterales.
 *
 * @param {Object} datos Objeto con los datos del evento a mostrar.
 */
function abrirModalConDatos(datos) {
    // Imagen
    const img = document.getElementById('modal-imagen');
    img.src = datos.imagen || '';

    // Título
    document.getElementById('modalEventoLabel').textContent = datos.titulo || '';

    // Precio
    const badgePrecio = document.getElementById('modal-precio');
    const esPago = datos.precio && datos.precio !== 'Gratuito';
    badgePrecio.textContent    = datos.precio || 'Gratuito';
    badgePrecio.className      = 'badge fs-6 ms-3 flex-shrink-0 ' + (esPago ? 'bg-primary' : 'bg-success');

    // Fechas
    document.getElementById('modal-inicio').textContent = datos.inicio || '';
    const finWrapper = document.getElementById('modal-fin-wrapper');
    if (datos.fin) {
        document.getElementById('modal-fin').textContent = datos.fin;
        finWrapper.classList.remove('d-none');
    } else {
        finWrapper.classList.add('d-none');
    }

    // Descripción: se usa pre-line en CSS para respetar saltos de línea del textarea
    document.getElementById('modal-descripcion').textContent = datos.descripcion || '';

    // Botón Google Maps
    const mapsBtn = document.getElementById('modal-maps-btn');
    if (datos.mapsUrl) {
        mapsBtn.href = datos.mapsUrl;
        mapsBtn.classList.remove('d-none');
    } else {
        mapsBtn.classList.add('d-none');
    }

    // Mostrar el modal de Bootstrap 5
    const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
    modal.show();
}

/**
 * Abre el modal desde las tarjetas de la lista lateral.
 * Lee los datos de los atributos data-* de la tarjeta clicada.
 *
 * @param {HTMLElement} el Elemento .evento-card clicado.
 */
function abrirModalEvento(el) {
    abrirModalConDatos({
        titulo:      el.dataset.titulo,
        inicio:      el.dataset.inicio,
        fin:         el.dataset.fin || null,
        precio:      el.dataset.precio,
        descripcion: el.dataset.descripcion,
        imagen:      el.dataset.imagen,
        mapsUrl:     el.dataset.maps || null,
    });
}
</script>
@endpush
