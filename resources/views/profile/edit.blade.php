<x-app-layout>
    <x-slot:title>Mi cuenta</x-slot:title>
    <x-slot name="header">
        <h1 class="h3 mb-0">Mi cuenta</h1>
    </x-slot>

    <div class="row g-4">
        <div class="col-12">
            <section class="card">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>
        </div>
        <div class="col-12">
            <section class="card">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
