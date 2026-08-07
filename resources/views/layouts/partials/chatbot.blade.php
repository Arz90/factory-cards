{{--
    ================================================================
    CHATBOT FLOTANTE — Widget de asistente virtual nativo
    ================================================================
    Estructura:
      1. #chatbot-widget     → Contenedor fijo en esquina inferior izquierda
         ├── .chatbot-mascot → Imagen animada (float) + fallback icono
         └── .chatbot-bubble → Bocadillo que aparece a los 3 seg
      2. #chatbot-ventana    → Ventana de chat (oculta por defecto)
         ├── .chatbot-header → Cabecera verde con nombre y botón cerrar
         ├── .chatbot-mensajes → Área de mensajes
         ├── .chatbot-opciones → Botones de respuesta rápida
         └── .chatbot-form   → Formulario de contacto (oculto por defecto)
    ================================================================
--}}

{{-- ── WIDGET FLOTANTE ─────────────────────────────────────────────── --}}
<div id="chatbot-widget" aria-label="Abrir asistente virtual FactoryBot">

    {{-- Bocadillo de saludo — aparece 3 seg después de cargar la página --}}
    <div id="chatbot-bubble" class="chatbot-bubble" role="status" aria-live="polite">
        ¡Hola! 👋 ¿Te ayudo a encontrar tu carta?
        <button id="chatbot-bubble-cerrar" class="chatbot-bubble-cerrar" aria-label="Cerrar saludo">×</button>
    </div>

    {{-- Mascota animada — usa la imagen o muestra icono de fallback --}}
    <button id="chatbot-toggle" class="chatbot-toggle-btn" aria-expanded="false" aria-controls="chatbot-ventana"
            title="Abrir o cerrar el asistente FactoryBot">
        {{-- Imagen principal: se oculta con JS si no carga --}}
        <img
            id="chatbot-mascot-img"
            src="{{ asset('images/chatbot-mascot.png') }}"
            alt="FactoryBot — asistente virtual de Factory Cards"
            class="chatbot-mascot-img"
            onerror="this.style.display='none'; document.getElementById('chatbot-mascot-fallback').style.display='flex';"
        >
        {{-- Fallback: icono Bootstrap si la imagen no existe --}}
        <div id="chatbot-mascot-fallback" class="chatbot-mascot-fallback" style="display:none;" aria-hidden="true">
            <i class="bi bi-robot"></i>
        </div>
    </button>

</div>

{{-- ── VENTANA DEL CHAT ─────────────────────────────────────────────── --}}
<div id="chatbot-ventana" class="chatbot-ventana" aria-hidden="true" role="dialog"
     aria-label="Chat con FactoryBot" aria-modal="false">

    {{-- Cabecera --}}
    <div class="chatbot-header">
        <div class="d-flex align-items-center gap-2">
            {{-- Punto de estado online --}}
            <span class="chatbot-status-dot" aria-hidden="true"></span>
            <div>
                <div class="chatbot-nombre">FactoryBot</div>
                <div class="chatbot-subtitulo">Asistente de Factory Cards</div>
            </div>
        </div>
        <button id="chatbot-cerrar" class="chatbot-btn-cerrar" aria-label="Cerrar chat">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Área de mensajes --}}
    <div id="chatbot-mensajes" class="chatbot-mensajes" role="log" aria-live="polite" aria-label="Mensajes del chat">

        {{-- Mensaje de bienvenida inicial --}}
        <div class="chatbot-msg chatbot-msg--bot">
            <div class="chatbot-msg-burbuja">
                👋 ¡Hola! Soy <strong>FactoryBot</strong>, el asistente de Factory Cards.<br>
                ¿En qué puedo ayudarte hoy?
            </div>
        </div>

    </div>

    {{-- Opciones rápidas --}}
    <div id="chatbot-opciones" class="chatbot-opciones">
        <button class="chatbot-opcion" data-accion="envios">
            📦 Envíos y Entregas
        </button>
        <button class="chatbot-opcion" data-accion="torneos">
            🏆 Próximos Torneos
        </button>
        <button class="chatbot-opcion" data-accion="vender">
            💰 Vender mis cartas
        </button>
        <button class="chatbot-opcion" data-accion="contacto">
            ✉️ Dejar un mensaje
        </button>
    </div>

    {{-- Formulario de contacto (oculto hasta que el usuario pulse "Dejar un mensaje") --}}
    <form id="chatbot-form" class="chatbot-form" novalidate style="display:none;">
        @csrf
        <div class="mb-2">
            <input
                type="text"
                name="nombre"
                id="chatbot-nombre"
                class="form-control form-control-sm"
                placeholder="Tu nombre *"
                required
                maxlength="100"
                autocomplete="name"
            >
        </div>
        <div class="mb-2">
            <input
                type="email"
                name="email"
                id="chatbot-email"
                class="form-control form-control-sm"
                placeholder="Tu email *"
                required
                maxlength="150"
                autocomplete="email"
            >
        </div>
        <div class="mb-2">
            <textarea
                name="mensaje"
                id="chatbot-mensaje"
                class="form-control form-control-sm"
                placeholder="¿En qué podemos ayudarte? *"
                rows="3"
                required
                maxlength="1000"
            ></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" id="chatbot-form-submit" class="btn btn-sm chatbot-btn-enviar flex-grow-1">
                Enviar mensaje
            </button>
            <button type="button" id="chatbot-form-cancelar" class="btn btn-sm btn-outline-secondary">
                Cancelar
            </button>
        </div>
    </form>

</div>

{{-- ──────────────────────────────────────────────────────────────────── --}}
{{-- JS del Chatbot — inyectado al final del componente para que use     --}}
{{-- las rutas de Laravel y el token CSRF ya generados en la vista.      --}}
{{-- ──────────────────────────────────────────────────────────────────── --}}
<script>
(function () {
    'use strict';

    // ── Constantes de configuración ─────────────────────────────────────
    const RUTA_EVENTOS  = @json(route('chatbot.eventos'));
    const RUTA_CONTACTO = @json(route('chatbot.contacto'));
    const TOKEN_CSRF    = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── Referencias a elementos del DOM ────────────────────────────────
    const widget        = document.getElementById('chatbot-widget');
    const ventana       = document.getElementById('chatbot-ventana');
    const toggleBtn     = document.getElementById('chatbot-toggle');
    const cerrarBtn     = document.getElementById('chatbot-cerrar');
    const bubble        = document.getElementById('chatbot-bubble');
    const bubbleCerrar  = document.getElementById('chatbot-bubble-cerrar');
    const mensajesArea  = document.getElementById('chatbot-mensajes');
    const opcionesArea  = document.getElementById('chatbot-opciones');
    const formulario    = document.getElementById('chatbot-form');
    const formCancelar  = document.getElementById('chatbot-form-cancelar');

    // ── Estado interno ──────────────────────────────────────────────────
    let ventanaAbierta = false;

    // ── Bocadillo: aparece a los 3 segundos si la ventana está cerrada ──
    bubble.style.display = 'none';
    setTimeout(function () {
        if (!ventanaAbierta) {
            bubble.style.display = 'flex';
            bubble.classList.add('chatbot-bubble--visible');
        }
    }, 3000);

    bubbleCerrar.addEventListener('click', function (e) {
        e.stopPropagation();
        ocultarBocadillo();
    });

    function ocultarBocadillo() {
        bubble.classList.remove('chatbot-bubble--visible');
        setTimeout(function () { bubble.style.display = 'none'; }, 300);
    }

    // ── Toggle: abrir / cerrar la ventana de chat ───────────────────────
    toggleBtn.addEventListener('click', function () {
        ventanaAbierta ? cerrarVentana() : abrirVentana();
    });

    cerrarBtn.addEventListener('click', function () {
        cerrarVentana();
    });

    function abrirVentana() {
        ventanaAbierta = true;
        ventana.classList.add('chatbot-ventana--visible');
        ventana.setAttribute('aria-hidden', 'false');
        toggleBtn.setAttribute('aria-expanded', 'true');
        ocultarBocadillo();
    }

    function cerrarVentana() {
        ventanaAbierta = false;
        ventana.classList.remove('chatbot-ventana--visible');
        ventana.setAttribute('aria-hidden', 'true');
        toggleBtn.setAttribute('aria-expanded', 'false');
    }

    // ── Añadir mensaje al área de chat ──────────────────────────────────
    /**
     * Añade un mensaje (burbuja) al área de chat.
     * @param {string}  html  Contenido HTML del mensaje
     * @param {'bot'|'usuario'} tipo
     */
    function agregarMensaje(html, tipo) {
        const div = document.createElement('div');
        div.className = 'chatbot-msg chatbot-msg--' + tipo;
        div.innerHTML = '<div class="chatbot-msg-burbuja">' + html + '</div>';
        mensajesArea.appendChild(div);
        // Scroll automático al último mensaje
        mensajesArea.scrollTop = mensajesArea.scrollHeight;
    }

    // ── Respuestas rápidas ──────────────────────────────────────────────
    opcionesArea.addEventListener('click', function (e) {
        const boton = e.target.closest('.chatbot-opcion');
        if (!boton) return;

        const accion = boton.dataset.accion;

        // Mostrar selección del usuario
        agregarMensaje(boton.textContent.trim(), 'usuario');

        switch (accion) {
            case 'envios':
                respuestaEnvios();
                break;
            case 'torneos':
                respuestaTorneos();
                break;
            case 'vender':
                respuestaVender();
                break;
            case 'contacto':
                respuestaContacto();
                break;
        }
    });

    // ── Respuesta: Envíos y Entregas ────────────────────────────────────
    function respuestaEnvios() {
        agregarMensaje(
            '📦 <strong>Política de envíos:</strong><br><br>' +
            '• <strong>Envío gratis</strong> en pedidos desde <strong>70 €</strong><br>' +
            '• Entrega en <strong>24–48 h</strong> laborables (Península)<br>' +
            '• Baleares y Canarias: consultar plazos<br>' +
            '• Recogida en tienda disponible sin coste<br><br>' +
            '¿Necesitas algo más?',
            'bot'
        );
    }

    // ── Respuesta: Próximos Torneos (consulta BD vía AJAX) ──────────────
    function respuestaTorneos() {
        agregarMensaje('⏳ Consultando próximos torneos…', 'bot');

        fetch(RUTA_EVENTOS, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': TOKEN_CSRF },
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            // Eliminar el mensaje de "cargando"
            mensajesArea.lastElementChild.remove();

            if (!data.eventos || data.eventos.length === 0) {
                agregarMensaje(
                    '🏆 No hay torneos próximos programados de momento.<br>' +
                    'Visita nuestra <a href="/eventos" class="chatbot-link">página de eventos</a> para mantenerte al día.',
                    'bot'
                );
                return;
            }

            // Construir lista de eventos
            let html = '🏆 <strong>Próximos torneos:</strong><br><br>';
            data.eventos.forEach(function (e) {
                html += '• <strong>' + e.titulo + '</strong><br>';
                html += '&nbsp;&nbsp;📅 ' + e.fecha + ' — ' + e.precio + '<br><br>';
            });
            html += '<a href="/eventos" class="chatbot-link">Ver calendario completo →</a>';

            agregarMensaje(html, 'bot');
        })
        .catch(function (err) {
            console.error('[ChatbotWidget] Error al cargar eventos:', err);
            mensajesArea.lastElementChild.remove();
            agregarMensaje(
                '⚠️ No he podido cargar los torneos en este momento. ' +
                'Visita nuestra <a href="/eventos" class="chatbot-link">página de eventos</a>.',
                'bot'
            );
        });
    }

    // ── Respuesta: Vender mis cartas ────────────────────────────────────
    function respuestaVender() {
        agregarMensaje(
            '💰 <strong>¿Quieres vender tus cartas?</strong><br><br>' +
            '¡Compramos cartas sueltas y colecciones completas!<br><br>' +
            '1️⃣ <strong>Valoración:</strong> Trae tus cartas a la tienda o mándanos una lista por email.<br>' +
            '2️⃣ <strong>Precio justo:</strong> Valoramos según el mercado actual.<br>' +
            '3️⃣ <strong>Cobro inmediato:</strong> En efectivo o crédito en tienda (+10% extra).<br><br>' +
            '📍 Pásate por la tienda o usa el formulario de contacto.',
            'bot'
        );
    }

    // ── Respuesta: Dejar un mensaje ─────────────────────────────────────
    function respuestaContacto() {
        agregarMensaje(
            '✉️ Claro, rellena el formulario y te responderemos lo antes posible:',
            'bot'
        );
        // Mostrar formulario y ocultar opciones
        opcionesArea.style.display = 'none';
        formulario.style.display   = 'block';
        document.getElementById('chatbot-nombre').focus();
    }

    // ── Botón Cancelar formulario ───────────────────────────────────────
    formCancelar.addEventListener('click', function () {
        formulario.style.display   = 'block';
        formulario.style.display   = 'none';
        opcionesArea.style.display = '';
        formulario.reset();
    });

    // ── Envío del formulario de contacto (AJAX) ─────────────────────────
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.getElementById('chatbot-form-submit');
        submitBtn.disabled    = true;
        submitBtn.textContent = 'Enviando…';

        const cuerpo = new FormData(formulario);

        fetch(RUTA_CONTACTO, {
            method: 'POST',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': TOKEN_CSRF,
            },
            body: cuerpo,
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            formulario.style.display   = 'none';
            opcionesArea.style.display = '';
            formulario.reset();

            if (data.ok) {
                agregarMensaje('✅ ' + data.mensaje, 'bot');
            } else {
                agregarMensaje('⚠️ Ha ocurrido un error. Por favor, inténtalo de nuevo.', 'bot');
            }
        })
        .catch(function (err) {
            console.error('[ChatbotWidget] Error al enviar mensaje:', err);
            agregarMensaje('⚠️ No se pudo enviar el mensaje. Revisa tu conexión e inténtalo de nuevo.', 'bot');
        })
        .finally(function () {
            submitBtn.disabled    = false;
            submitBtn.textContent = 'Enviar mensaje';
        });
    });

})();
</script>
