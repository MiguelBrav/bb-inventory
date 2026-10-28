<section>
    <header>
        <h2 class="h5">Datos del usuario</h2>
        <p class="text-secondary">Actualiza tu nombre. El correo electrónico no se puede modificar.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" name="email" type="email" :value="$user->email" readonly autocomplete="username" class="bg-light" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>Guardar cambios</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-success mb-0" role="status">Cambios guardados.</p>
            @endif
        </div>
    </form>
</section>
