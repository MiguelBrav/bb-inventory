@extends('layouts.app')

@section('title', 'Registrar movimiento')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="{{ route('stock-movements.index') }}">← Movimientos</a>
        <h1 class="h2 mt-2 mb-0">Registrar movimiento</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @include('stock_movements._form', [
                'stockMovement' => null,
                'formAction' => route('stock-movements.store'),
                'formMethod' => 'POST',
                'cancelRoute' => route('stock-movements.index'),
            ])
        </div>
    </div>
@endsection
