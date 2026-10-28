@extends('layouts.app')

@section('title', 'Editar categoría')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="{{ route('categories.index') }}">← Categorías</a>
        <h1 class="h2 mt-2 mb-1">{{ $category->name }}</h1>
        <p class="text-secondary mb-0">Consulta y edita la información de la categoría.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <section class="card border-0 shadow-sm h-100" aria-labelledby="category-summary-heading">
                <div class="card-body">
                    <h2 class="h5 mb-3" id="category-summary-heading">Información de la categoría</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nombre</dt>
                        <dd class="col-sm-7">{{ $category->name }}</dd>
                        <dt class="col-sm-5">Slug</dt>
                        <dd class="col-sm-7">{{ $category->slug }}</dd>
                        <dt class="col-sm-5">Productos asignados</dt>
                        <dd class="col-sm-7">{{ $category->products_count }}</dd>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-lg-7">
            <section class="card border-0 shadow-sm" aria-labelledby="category-edit-heading">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3" id="category-edit-heading">Editar categoría</h2>
                    <form method="POST" action="{{ route('categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        @include('categories._form')
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">Guardar cambios</button>
                            <a class="btn btn-outline-secondary" href="{{ route('categories.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
