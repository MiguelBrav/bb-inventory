@extends('layouts.app')

@section('title', 'Crear usuario')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="{{ route('users.index') }}">← Usuarios</a>
        <h1 class="h2 mt-2 mb-0">Crear usuario</h1>
        <p class="text-secondary mt-2 mb-0">La cuenta nueva no tendrá permisos de administrador.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nombre</label>
                        <input class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" type="text" value="{{ old('name') }}"
                               maxlength="255" autocomplete="name" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" type="email" value="{{ old('email') }}"
                               maxlength="255" autocomplete="email" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="password">Contraseña inicial</label>
                        <input class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" type="password"
                               autocomplete="new-password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation"
                               type="password" autocomplete="new-password" required>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">Crear usuario</button>
                    <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
