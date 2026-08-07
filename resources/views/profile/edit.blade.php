{{--
    Vista: profile/edit
    Propósito: Página de perfil de usuario generada por Breeze.
    Adaptada para usar nuestro layout principal con Bootstrap 5
    en lugar del layout Tailwind original de Breeze.
    Incluye: actualizar datos, cambiar contraseña y eliminar cuenta.
--}}
@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 720px;">

    <h2 class="fw-bold mb-4">Mi perfil</h2>

    {{-- Sección: Actualizar información personal --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">Información de la cuenta</h5>
        </div>
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- Sección: Cambiar contraseña --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">Cambiar contraseña</h5>
        </div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- Sección: Eliminar cuenta (zona de peligro) --}}
    <div class="card shadow-sm border-danger">
        <div class="card-header bg-white py-3 border-bottom border-danger">
            <h5 class="mb-0 fw-semibold text-danger">Zona de peligro</h5>
        </div>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
