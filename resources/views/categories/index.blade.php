@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Categorías</h1>
            <p class="text-secondary mb-0">Organiza los productos del inventario.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-danger" href="{{ route('categories.export.pdf') }}">Exportar PDF</a>
            <a class="btn btn-outline-success" href="{{ route('categories.export') }}">Exportar Excel</a>
            <a class="btn btn-primary" href="{{ route('categories.create') }}">Nueva categoría</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Productos</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td class="text-secondary">{{ $category->slug }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('categories.edit', $category) }}">Editar</a>
                            <form class="d-inline" method="POST" action="{{ route('categories.destroy', $category) }}"
                                  onsubmit="return confirm('¿Eliminar esta categoría? Los productos quedarán sin categoría.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-secondary py-4" colspan="4">
                            No hay categorías para mostrar.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer bg-white">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
