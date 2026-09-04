{{--
    Vista: sell-cards
    Propósito: Landing page de captación de segunda mano.
    Permite a particulares enviar solicitudes de venta de cartas TCG.
    El formulario envía fotos/listas al equipo para tasación en 48 h.
--}}
@extends('layouts.app')

@section('title', 'Vende tus Cartas TCG — Factory Cards')
@section('meta_description', 'Vende tus cartas Pokémon, Magic, One Piece y más en Factory Cards. Tasación profesional en 48h. Obtén un 20% extra eligiendo Crédito de Tienda.')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════════════ --}}
<section class="sell-hero">
    <div class="container position-relative">
        <div class="row align-items-center g-4">

            <div class="col-lg-7">
                {{-- Badge superior --}}
                <div class="sell-hero-badge">
                    <i class="bi bi-lightning-fill"></i>
                    Compramos tu colección
                </div>

                {{-- Título principal --}}
                <h1 class="sell-hero-titulo mb-3">
                    CONVIERTE TUS CARTAS<br>
                    EN <span class="sell-highlight">DINERO O SALDO</span>
                </h1>

                <p class="sell-hero-subtitulo mb-4">
                    Tasamos tu colección de Pokémon, Magic, One Piece y mucho más.
                    Proceso 100% seguro, pago rápido y transparente.
                </p>

                {{-- Bonus destacado --}}
                <div class="sell-bonus-box mb-4">
                    <i class="bi bi-gift-fill fs-4" style="color:var(--fc-amarillo)"></i>
                    <div>
                        Elige cobrar en <strong>Crédito de Tienda</strong> y obtén
                        <span class="sell-bonus-badge ms-1">+20% EXTRA</span>
                    </div>
                </div>

                {{-- Trust badges --}}
                <div class="sell-trust-bar">
                    <div class="sell-trust-item">
                        <i class="bi bi-shield-check-fill"></i>
                        Tasación profesional
                    </div>
                    <div class="sell-trust-item">
                        <i class="bi bi-clock-fill"></i>
                        Respuesta en 48 h
                    </div>
                    <div class="sell-trust-item">
                        <i class="bi bi-cash-coin"></i>
                        Pago al instante
                    </div>
                    <div class="sell-trust-item">
                        <i class="bi bi-star-fill"></i>
                        Sin intermediarios
                    </div>
                </div>
            </div>

            {{-- Imagen decorativa / ilustración (solo desktop) --}}
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div class="text-center" style="opacity:.9">
                    <i class="bi bi-collection-fill" style="font-size:9rem;color:var(--fc-verde);opacity:.35;"></i>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     SECCIÓN 3 PASOS
══════════════════════════════════════════════════════════════ --}}
<section class="sell-steps">
    <div class="container">

        <div class="text-center mb-4">
            <h2 class="fw-black" style="font-size:clamp(1.4rem,3vw,2rem);">
                ¿Cómo funciona?
            </h2>
            <p class="text-muted" style="font-size:.92rem;">
                Tres pasos sencillos para convertir tus cartas en efectivo o saldo.
            </p>
        </div>

        <div class="row g-4 align-items-stretch justify-content-center">

            {{-- Paso 1 --}}
            <div class="col-sm-6 col-lg-3">
                <div class="sell-step-card">
                    <div class="sell-step-num">1</div>
                    <div class="sell-step-icon">
                        <i class="bi bi-images"></i>
                    </div>
                    <div class="sell-step-titulo">Envíanos tu lista o fotos</div>
                    <p class="sell-step-desc">
                        Rellena el formulario y adjunta fotos de tus cartas o un excel con la lista.
                        Cuanta más info, más rápida la tasación.
                    </p>
                </div>
            </div>

            {{-- Conector --}}
            <div class="col-lg-1 sell-step-connector">
                <i class="bi bi-chevron-right"></i>
            </div>

            {{-- Paso 2 --}}
            <div class="col-sm-6 col-lg-3">
                <div class="sell-step-card">
                    <div class="sell-step-num">2</div>
                    <div class="sell-step-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <div class="sell-step-titulo">Tasación Profesional</div>
                    <p class="sell-step-desc">
                        Nuestro equipo analiza tu colección con precios actualizados del mercado.
                        Recibirás una oferta detallada en menos de <strong>48 horas</strong>.
                    </p>
                </div>
            </div>

            {{-- Conector --}}
            <div class="col-lg-1 sell-step-connector">
                <i class="bi bi-chevron-right"></i>
            </div>

            {{-- Paso 3 --}}
            <div class="col-sm-6 col-lg-3">
                <div class="sell-step-card">
                    <div class="sell-step-num">3</div>
                    <div class="sell-step-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="sell-step-titulo">Cobra al Instante</div>
                    <p class="sell-step-desc">
                        Si aceptas la oferta, recibes el pago en efectivo, PayPal o transferencia.
                        O bien saldo de tienda con <strong>+20% de bonificación</strong>.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     SECCIÓN BULK — Venta a Granel
══════════════════════════════════════════════════════════════ --}}
<section class="sell-bulk-section">
    <div class="container">

        {{-- Cabecera --}}
        <div class="text-center mb-5">
            <div class="sell-hero-badge mx-auto mb-3" style="display:inline-flex;">
                <i class="bi bi-boxes"></i>
                Bulk / Granel
            </div>
            <h2 class="fw-black mb-2" style="font-size:clamp(1.4rem,3vw,2rem);">
                Venta a Granel <span style="color:var(--fc-verde)">(Bulk)</span>
            </h2>
            <p class="text-muted mx-auto" style="max-width:520px;font-size:.95rem;">
                ¿Tienes cajas llenas de cartas repetidas? Te las compramos por volumen.
                Precios fijos, sin tasación individual.
            </p>
        </div>

        <div class="row g-4 align-items-start">

            {{-- ── Tabla de tarifas ── --}}
            <div class="col-lg-7">
                <div class="sell-bulk-card">
                    <div class="sell-bulk-card-header">
                        <i class="bi bi-tag-fill me-2"></i>Tarifas de compra por cada 1.000 cartas
                    </div>
                    <div class="sell-bulk-card-body">
                        <div class="sell-bulk-row sell-bulk-row--highlight">
                            <div class="sell-bulk-tipo">
                                <span class="sell-bulk-dot" style="background:#6B7280;"></span>
                                <div>
                                    <strong>Comunes / Infrecuentes</strong>
                                    <span class="sell-bulk-desc">C / UC — cualquier juego</span>
                                </div>
                            </div>
                            <div class="sell-bulk-precio">12,00 €<span>/1.000</span></div>
                        </div>
                        <div class="sell-bulk-row">
                            <div class="sell-bulk-tipo">
                                <span class="sell-bulk-dot" style="background:#F59E0B;"></span>
                                <div>
                                    <strong>Raras (Sin brillo)</strong>
                                    <span class="sell-bulk-desc">R — sin holo, sin reverse</span>
                                </div>
                            </div>
                            <div class="sell-bulk-precio">20,00 €<span>/1.000</span></div>
                        </div>
                        <div class="sell-bulk-row">
                            <div class="sell-bulk-tipo">
                                <span class="sell-bulk-dot" style="background:#8B5CF6;"></span>
                                <div>
                                    <strong>Holos / Reverse Holos</strong>
                                    <span class="sell-bulk-desc">Holo Rare, Reverse Holo</span>
                                </div>
                            </div>
                            <div class="sell-bulk-precio">35,00 €<span>/1.000</span></div>
                        </div>
                        <div class="sell-bulk-row">
                            <div class="sell-bulk-tipo">
                                <span class="sell-bulk-dot" style="background:#29A44F;"></span>
                                <div>
                                    <strong>Energías Básicas</strong>
                                    <span class="sell-bulk-desc">Solo cartas de energía</span>
                                </div>
                            </div>
                            <div class="sell-bulk-precio">2,00 €<span>/1.000</span></div>
                        </div>

                        {{-- CTA informativo --}}
                        <div class="sell-bulk-cta">
                            <i class="bi bi-info-circle-fill me-2" style="color:var(--fc-verde);flex-shrink:0;"></i>
                            <span>
                                Para vender tu granel, selecciona
                                <strong>"Cartas a granel (Bulk)"</strong> en el formulario de abajo
                                e indícanos las cantidades estimadas en los comentarios.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Calculadora rápida ── --}}
            <div class="col-lg-5">
                <div class="sell-calc-card">
                    <div class="sell-calc-header">
                        <i class="bi bi-calculator-fill me-2"></i>Calculadora rápida
                    </div>
                    <div class="sell-calc-body">
                        <p class="sell-calc-hint">
                            Estima al instante cuánto vale tu granel.
                        </p>

                        <label class="sell-calc-label" for="calc-tipo">Tipo de carta</label>
                        <select id="calc-tipo" class="sell-calc-select mb-3">
                            <option value="12">Comunes / Infrecuentes — 12 €/1.000</option>
                            <option value="20">Raras (Sin brillo) — 20 €/1.000</option>
                            <option value="35">Holos / Reverse Holos — 35 €/1.000</option>
                            <option value="2">Energías Básicas — 2 €/1.000</option>
                        </select>

                        <label class="sell-calc-label" for="calc-cantidad">¿Cuántas cartas tienes?</label>
                        <input type="number"
                               id="calc-cantidad"
                               class="sell-calc-input mb-4"
                               placeholder="Ej: 3500"
                               min="0"
                               step="100">

                        {{-- Resultado --}}
                        <div class="sell-calc-resultado" id="calc-resultado">
                            <div class="sell-calc-resultado-label">Estimación aproximada</div>
                            <div class="sell-calc-resultado-valor" id="calc-valor">— €</div>
                            <div class="sell-calc-resultado-sub" id="calc-sub"></div>
                        </div>

                        <p class="sell-calc-aviso">
                            * Estimación orientativa. El precio final se confirma tras la tasación.
                            Precios por cada 1.000 cartas o fracción.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     SECCIÓN FORMULARIO + SIDEBAR
══════════════════════════════════════════════════════════════ --}}
<section class="sell-form-section">
    <div class="container">

        {{-- Alerta de éxito tras envío --}}
        @if(session('success'))
            <div class="sell-alert-success mb-4">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="row g-4 g-lg-5 align-items-start">

            {{-- ── FORMULARIO (columna principal) ── --}}
            <div class="col-lg-8">
                <div class="sell-form-card">

                    {{-- Cabecera del card --}}
                    <div class="sell-form-header">
                        <h2><i class="bi bi-send-fill me-2"></i>Solicita tu Tasación Gratuita</h2>
                        <p>Sin compromiso. Te respondemos en menos de 48 horas.</p>
                    </div>

                    {{-- Cuerpo del formulario --}}
                    <div class="sell-form-body">
                        <form action="{{ route('sell-cards.store') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              novalidate>
                            @csrf

                            {{-- Datos personales --}}
                            <h6 class="text-uppercase fw-black mb-3"
                                style="font-size:.72rem;letter-spacing:1px;color:#9CA3AF;">
                                Tus datos de contacto
                            </h6>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="nombre"
                                           name="nombre"
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre') }}"
                                           placeholder="Adrián"
                                           required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="apellidos"
                                           name="apellidos"
                                           class="form-control @error('apellidos') is-invalid @enderror"
                                           value="{{ old('apellidos') }}"
                                           placeholder="García López"
                                           required>
                                    @error('apellidos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()?->email) }}"
                                           placeholder="tu@email.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel"
                                           id="telefono"
                                           name="telefono"
                                           class="form-control"
                                           value="{{ old('telefono') }}"
                                           placeholder="+34 600 000 000">
                                </div>
                            </div>

                            <div class="sell-divider"></div>

                            {{-- Datos de la colección --}}
                            <h6 class="text-uppercase fw-black mb-3"
                                style="font-size:.72rem;letter-spacing:1px;color:#9CA3AF;">
                                Tu colección
                            </h6>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="juego" class="form-label">
                                        ¿Qué juego nos vendes? <span class="text-danger">*</span>
                                    </label>
                                    <select id="juego"
                                            name="juego"
                                            class="form-select @error('juego') is-invalid @enderror"
                                            required>
                                        <option value="">— Selecciona el juego —</option>
                                        {{-- Franquicias dinámicas desde la BD --}}
                                        @foreach($franquicias as $franquicia)
                                            <option value="{{ $franquicia->id }}"
                                                {{ old('juego') == $franquicia->id ? 'selected' : '' }}>
                                                {{ $franquicia->name }}
                                            </option>
                                        @endforeach
                                        <option value="otro" {{ old('juego') == 'otro' ? 'selected' : '' }}>
                                            Otro juego
                                        </option>
                                    </select>
                                    @error('juego')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label for="tipo_coleccion" class="form-label">
                                        Tipo de colección <span class="text-danger">*</span>
                                    </label>
                                    <select id="tipo_coleccion"
                                            name="tipo_coleccion"
                                            class="form-select @error('tipo_coleccion') is-invalid @enderror"
                                            required>
                                        <option value="">— Selecciona —</option>
                                        <option value="cartas_valor"
                                            {{ old('tipo_coleccion') == 'cartas_valor' ? 'selected' : '' }}>
                                            Cartas sueltas de valor
                                        </option>
                                        <option value="coleccion_completa"
                                            {{ old('tipo_coleccion') == 'coleccion_completa' ? 'selected' : '' }}>
                                            Colección completa
                                        </option>
                                        <option value="bulk"
                                            {{ old('tipo_coleccion') == 'bulk' ? 'selected' : '' }}>
                                            Cartas a granel / Bulk
                                        </option>
                                    </select>
                                    @error('tipo_coleccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Subida de archivos --}}
                            <div class="mb-3">
                                <label class="form-label">
                                    Fotos o archivos de tu colección
                                    <span class="text-muted fw-normal">(opcional, hasta 10 archivos)</span>
                                </label>
                                <div class="sell-file-drop" id="sell-file-drop">
                                    <div class="sell-file-drop-inner">
                                        <i class="bi bi-cloud-arrow-up fs-2 text-muted d-block mb-2"></i>
                                        <div class="fw-700" style="font-size:.9rem;color:#374151;">
                                            Arrastra tus archivos aquí o haz clic para seleccionar
                                        </div>
                                        <div style="font-size:.75rem;color:#9CA3AF;margin-top:4px;">
                                            JPG, PNG, PDF, Excel, CSV — Máx. 10 MB por archivo
                                        </div>
                                        <div id="sell-file-names" class="mt-2"
                                             style="font-size:.78rem;color:var(--fc-verde);font-weight:700;"></div>
                                    </div>
                                    <input type="file"
                                           name="archivos[]"
                                           id="sell-archivos"
                                           multiple
                                           accept=".jpg,.jpeg,.png,.pdf,.xls,.xlsx,.csv"
                                           style="position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;">
                                </div>
                            </div>

                            {{-- Comentarios --}}
                            <div class="mb-4">
                                <label for="comentarios" class="form-label">
                                    Comentarios sobre el estado <span class="text-muted fw-normal">(opcional)</span>
                                </label>
                                <textarea id="comentarios"
                                          name="comentarios"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Ej: Cartas en estado Near Mint, guardadas en fundas desde hace 2 años. Incluye algunas holos de la primera generación...">{{ old('comentarios') }}</textarea>
                            </div>

                            <div class="sell-divider"></div>

                            {{-- Preferencia de cobro --}}
                            <h6 class="text-uppercase fw-black mb-3"
                                style="font-size:.72rem;letter-spacing:1px;color:#9CA3AF;">
                                Preferencia de cobro
                            </h6>

                            <div class="row g-3 mb-4">
                                {{-- Opción 1: Efectivo --}}
                                <div class="col-sm-6">
                                    <label class="sell-cobro-option w-100 h-100">
                                        <input type="radio"
                                               name="cobro"
                                               value="efectivo"
                                               {{ old('cobro', 'efectivo') == 'efectivo' ? 'checked' : '' }}>
                                        <div>
                                            <div class="sell-cobro-titulo">
                                                <i class="bi bi-cash-stack" style="color:var(--fc-verde)"></i>
                                                Efectivo
                                            </div>
                                            <p class="sell-cobro-desc">
                                                PayPal, transferencia bancaria o pago en tienda.
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Opción 2: Saldo de tienda --}}
                                <div class="col-sm-6">
                                    <label class="sell-cobro-option w-100 h-100">
                                        <input type="radio"
                                               name="cobro"
                                               value="saldo_tienda"
                                               {{ old('cobro') == 'saldo_tienda' ? 'checked' : '' }}>
                                        <div>
                                            <div class="sell-cobro-titulo">
                                                <i class="bi bi-wallet-fill" style="color:var(--fc-verde)"></i>
                                                Saldo de Tienda
                                                <span class="sell-bonus-inline">+20% EXTRA</span>
                                            </div>
                                            <p class="sell-cobro-desc">
                                                Bonificación automática del 20% sobre la tasación acordada.
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Botón de envío --}}
                            <button type="submit" class="sell-btn-submit">
                                <i class="bi bi-send-fill me-2"></i>
                                Enviar solicitud de tasación gratuita
                            </button>

                            <p class="sell-privacy-note mt-2">
                                <i class="bi bi-lock-fill me-1"></i>
                                Tus datos están protegidos y solo se usan para gestionar tu solicitud.
                                Sin spam, sin comisiones ocultas.
                            </p>

                        </form>
                    </div>
                </div>
            </div>

            {{-- ── SIDEBAR: Por qué Factory Cards ── --}}
            <div class="col-lg-4">
                <div class="sell-sidebar-card">
                    <h4><i class="bi bi-patch-check-fill me-2"></i>¿Por qué Factory Cards?</h4>

                    <div class="sell-why-item">
                        <i class="bi bi-graph-up-arrow"></i>
                        <div>
                            <strong>Precios del mercado real</strong>
                            <p>Consultamos Cardmarket y TCGPlayer diariamente para ofrecerte el mejor precio.</p>
                        </div>
                    </div>

                    <div class="sell-why-item">
                        <i class="bi bi-lightning-fill"></i>
                        <div>
                            <strong>Tasación en menos de 48 h</strong>
                            <p>Nuestro equipo de expertos valora tu colección con total transparencia.</p>
                        </div>
                    </div>

                    <div class="sell-why-item">
                        <i class="bi bi-shield-fill-check"></i>
                        <div>
                            <strong>Sin intermediarios ni comisiones</strong>
                            <p>Compramos directamente. No pagas comisiones de terceras plataformas.</p>
                        </div>
                    </div>

                    <div class="sell-why-item">
                        <i class="bi bi-gift-fill" style="color:var(--fc-amarillo)"></i>
                        <div>
                            <strong>+20% en Crédito de Tienda</strong>
                            <p>Elige cobrar en saldo y tu colección vale automáticamente un 20% más.</p>
                        </div>
                    </div>

                    <div class="sell-why-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <div>
                            <strong>Envío o entrega en tienda</strong>
                            <p>Puedes enviarnos la colección o traerla personalmente a nuestra tienda física.</p>
                        </div>
                    </div>

                    {{-- Separador --}}
                    <div class="sell-divider"></div>

                    {{-- CTA secundario --}}
                    <div class="text-center">
                        <p style="font-size:.8rem;color:rgba(255,255,255,.6);margin-bottom:.5rem;">
                            ¿Prefieres hablar antes de enviar?
                        </p>
                        <a href="#chatbot-widget"
                           class="btn btn-outline-light btn-sm w-100"
                           onclick="document.querySelector('.chatbot-toggle-btn')?.click(); return false;">
                            <i class="bi bi-chat-dots me-1"></i>
                            Habla con nuestro asistente
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
/**
 * Muestra los nombres de los archivos seleccionados en la zona de drop.
 */
document.getElementById('sell-archivos')?.addEventListener('change', function () {
    const label = document.getElementById('sell-file-names');
    const archivos = Array.from(this.files);
    if (!archivos.length) {
        label.textContent = '';
        return;
    }
    label.textContent = archivos.length === 1
        ? archivos[0].name
        : `${archivos.length} archivos seleccionados`;
});

/**
 * Calculadora de venta a granel.
 * Calcula el precio estimado en tiempo real según tipo y cantidad de cartas.
 * Precio por cada 1.000 cartas (fracción incluida).
 */
(function () {
    const elTipo      = document.getElementById('calc-tipo');
    const elCantidad  = document.getElementById('calc-cantidad');
    const elValor     = document.getElementById('calc-valor');
    const elSub       = document.getElementById('calc-sub');

    if (!elTipo || !elCantidad) return;

    function calcular() {
        const precioPorMil = parseFloat(elTipo.value) || 0;
        const cantidad     = parseFloat(elCantidad.value) || 0;

        if (cantidad <= 0) {
            elValor.textContent = '— €';
            elSub.textContent   = '';
            return;
        }

        // Precio proporcional (fracción contabilizada)
        const total = (cantidad / 1000) * precioPorMil;

        // Formatear como precio en español
        elValor.textContent = total.toLocaleString('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' €';

        elSub.textContent = cantidad.toLocaleString('es-ES') + ' cartas × '
            + precioPorMil.toLocaleString('es-ES', { minimumFractionDigits: 2 })
            + ' €/1.000';
    }

    elTipo.addEventListener('change', calcular);
    elCantidad.addEventListener('input', calcular);
})();
</script>
@endpush
