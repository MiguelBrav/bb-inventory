@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Usuarios</h1>
            <p class="text-secondary mb-0">Administra las cuentas de acceso al inventario.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('users.create') }}">Crear usuario</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo electrónico</th>
                    <th scope="col">Tipo</th>
                    <th scope="col" class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->is_admin)
                                <span class="badge text-bg-primary">Administrador</span>
                            @else
                                <span class="badge text-bg-secondary">Usuario</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($user->is_admin)
                                <span class="text-secondary">Cuenta protegida</span>
                            @else
                                <div class="d-inline-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="{{ route('users.edit', $user) }}">Editar</a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                          onsubmit="return confirm('¿Eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
@endsection
