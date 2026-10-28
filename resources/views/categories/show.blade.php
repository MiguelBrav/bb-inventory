@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a class="link-secondary text-decoration-none" href="{{ route('categories.index') }}">← Categorías</a>
            <h1 class="h2 mt-2 mb-1">{{ $category->name }}</h1>
            <span class="text-secondary">Slug: {{ $category->slug }}</span>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('categories.edit', $category) }}">Editar</a>
            <form method="POST" action="{{ route('categories.destroy', $category) }}"
                  onsubmit="return confirm('¿Eliminar esta categoría? Los productos quedarán sin categoría.')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-3">Información de la categoría</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $category->name }}</dd>
                <dt class="col-sm-4">Slug</dt>
                <dd class="col-sm-8">{{ $category->slug }}</dd>
                <dt class="col-sm-4">Productos asignados</dt>
                <dd class="col-sm-8">{{ $category->products_count }}</dd>
            </dl>
        </div>
    </div>
@endsection
