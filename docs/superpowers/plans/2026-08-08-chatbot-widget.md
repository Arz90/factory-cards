# Chatbot Widget Implementation Plan

> **For agentic workers:** REQUIRED: Use superpowers:executing-plans to implement this plan.

**Goal:** Widget de chatbot flotante 100% nativo — mascota animada + ventana de chat con 4 opciones rápidas y formulario de contacto integrado.

**Architecture:** Partial Blade `layouts/partials/chatbot.blade.php` incluido en `app.blade.php`. CSS en `app.css`. JS Vanilla en `app.js`. Dos rutas backend: una GET para eventos próximos (JSON) y una POST para guardar mensaje de contacto (log). ChatbotController gestiona ambas.

**Tech Stack:** Laravel 11, Blade, Bootstrap 5, Vanilla JS, CSS @keyframes

---

## Chunk 1: Backend

### Task 1: ChatbotController + Rutas

**Files:**
- Create: `app/Http/Controllers/ChatbotController.php`
- Modify: `routes/web.php`

- [ ] Crear `ChatbotController` con métodos `eventosProximos()` y `enviarMensaje()`
- [ ] Añadir rutas `/chatbot/eventos` (GET) y `/chatbot/contacto` (POST) en `web.php`
- [ ] Commit: `feat: Add ChatbotController with events and contact routes`

---

## Chunk 2: Frontend

### Task 2: Partial Blade del Chatbot

**Files:**
- Create: `resources/views/layouts/partials/chatbot.blade.php`
- Modify: `resources/views/layouts/app.blade.php`

- [ ] Crear la vista parcial con widget flotante, bocadillo, ventana de chat, opciones rápidas y formulario
- [ ] Incluir `@include` al final de `app.blade.php` (antes de Bootstrap JS)
- [ ] Commit: `feat: Add chatbot Blade partial and include in layout`

### Task 3: CSS del Chatbot

**Files:**
- Modify: `public/css/app.css`

- [ ] Añadir sección `/* CHATBOT WIDGET */` con animación `@keyframes float`, estilos del widget, bocadillo, ventana, mensajes, opciones rápidas, formulario
- [ ] Commit: `feat: Add chatbot CSS with float animation`

### Task 4: JS del Chatbot

**Files:**
- Modify: `public/js/app.js`

- [ ] Añadir sección `/* ── CHATBOT ──` con lógica toggle, bocadillo temporizador, quick replies y submit AJAX del formulario
- [ ] Commit incluido en commit final general

---

## Commit Final

```bash
git add .
git commit -m "feat: Implement 100% web-native Chatbot without external messaging dependencies"
git push
```
