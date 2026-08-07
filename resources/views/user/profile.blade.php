@extends('layouts.app')
@section('title', 'Mi perfil — Factory Cards')
@section('content')
<div class="container py-4" style="max-width:600px">
    <h1 class="h4 fw-bold mb-4">Mi perfil</h1>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
                <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"></div>
                <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}"></div>
                <div class="row g-2 mb-3">
                    <div class="col-4"><label class="form-label">CP</label><input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $user->postal_code) }}"></div>
                    <div class="col-8"><label class="form-label">Ciudad</label><input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}"></div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection
