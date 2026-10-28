@extends('layouts.app')

@section('title', 'Productos')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Productos</h1>
            <p class="text-secondary mb-0">Consulta y administra el inventario.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('products.create') }}">Nuevo producto</a>
    </div>

    <section class="card border-0 shadow-sm mb-4" aria-label="Filtro de productos">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET" action="{{ route('products.index') }}">
                <div class="col-sm-8 col-md-5">
                    <label class="form-label" for="category_id">Categoría</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit">Filtrar</button>
                    <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">Limpiar</a>
                </div>
            </form>
        </div>
    </section>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col">SKU</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Existencia</th>
                    <th scope="col">Precio de venta</th>
                    <th scope="col">Vencimiento</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    @php($lowStock = $product->stock <= $product->min_stock)
                    <tr @class(['table-warning' => $lowStock])>
                        <td class="text-nowrap">{{ $product->sku }}</td>
                        <td>
                            <span class="fw-semibold">{{ $product->name }}</span>
                            @if ($lowStock)
                                <span class="badge text-bg-warning ms-1">Stock bajo</span>
                            @endif
                        </td>
                        <td>{{ $product->category?->name ?? 'Sin categoría' }}</td>
                        <td>{{ $product->stock }} <span class="text-secondary">/ mín. {{ $product->min_stock }}</span></td>
                        <td>${{ number_format((float) $product->sale_price, 2, ',', '.') }}</td>
                        <td>{{ $product->expiration_date?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('products.edit', $product) }}">Editar</a>
                            <form class="d-inline" method="POST" action="{{ route('products.destroy', $product) }}"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-secondary py-4" colspan="7">No hay productos para mostrar.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="card-footer bg-white">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
