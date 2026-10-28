<x-guest-layout>
    <h1 class="h4 mb-3">Recuperar contraseña</h1>
    <p class="text-secondary">Ingresa tu correo electrónico y te enviaremos un enlace para restablecerla.</p>
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="small">Volver a iniciar sesión</a>
            <x-primary-button>
                Enviar enlace
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
