@extends('layouts.app')

@section('title', 'Nueva categoría')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="{{ route('categories.index') }}">← Categorías</a>
        <h1 class="h2 mt-2 mb-0">Nueva categoría</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                @include('categories._form')
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">Guardar categoría</button>
                    <a class="btn btn-outline-secondary" href="{{ route('categories.index') }}">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
