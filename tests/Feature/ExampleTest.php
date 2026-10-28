<?php

test('guests must authenticate before accessing the inventory', function () {
    $this->get('/')->assertRedirect(route('login'));
    $this->get(route('products.index'))->assertRedirect(route('login'));
    $this->get(route('login'))->assertOk()->assertSee('Iniciar sesión');
    $this->get('/register')->assertNotFound();
});
