<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">Mini Inventario</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation"
                aria-controls="mainNavigation" aria-expanded="false" aria-label="Abrir navegación">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavigation">
            <div class="navbar-nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">Panel</a>
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                   href="{{ route('products.index') }}">Productos</a>
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                   href="{{ route('categories.index') }}">Categorías</a>
                <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"
                   href="{{ route('stock-movements.index') }}">Movimientos</a>
                @can('manage-users')
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                       href="{{ route('users.index') }}">Usuarios</a>
                @endcan
            </div>
            <div class="navbar-nav ms-auto align-items-lg-center">
                <a class="nav-link" href="{{ route('profile.edit') }}">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}" class="d-flex">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
</nav>
