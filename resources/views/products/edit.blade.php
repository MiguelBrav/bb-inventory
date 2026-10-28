@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="{{ route('products.show', $product) }}">← Detalle del producto</a>
        <h1 class="h2 mt-2 mb-0">Editar producto</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('products.update', $product) }}">
                @csrf
                @method('PUT')
                @include('products._form')
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
                    <a class="btn btn-outline-secondary" href="{{ route('products.show', $product) }}">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
