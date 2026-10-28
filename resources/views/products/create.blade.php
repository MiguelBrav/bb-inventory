@extends('layouts.app')

@section('title', 'Nuevo producto')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="{{ route('products.index') }}">← Productos</a>
        <h1 class="h2 mt-2 mb-0">Nuevo producto</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('products.store') }}">
                @csrf
                @include('products._form')
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">Guardar producto</button>
                    <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
