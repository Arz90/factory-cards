@extends('layouts.app')
@section('title', 'Mi cuenta — Factory Cards')
@section('content')
<div class="container py-4">
    <h1 class="h3 fw-bold mb-4">Mi cuenta</h1>
    <p>Bienvenido, {{ auth()->user()->name }}.</p>
    <div class="list-group list-group-flush">
        <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">Mis pedidos</a>
        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">Mi perfil</a>
    </div>
</div>
@endsection
