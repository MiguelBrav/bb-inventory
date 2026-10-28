<?php

declare(strict_types=1);

return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'lowercase' => 'El campo :attribute debe estar en minúsculas.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'unique' => 'El valor de :attribute ya está en uso.',
    'current_password' => 'La contraseña actual no es correcta.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'numeric' => 'El campo :attribute debe ser numérico.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'exists' => 'El valor seleccionado para :attribute no es válido.',
    'in' => 'El valor seleccionado para :attribute no es válido.',
    'min' => 'El campo :attribute debe ser como mínimo :min.',
    'max' => 'El campo :attribute no puede superar :max.',
    'attributes' => [
        'category_id' => 'categoría',
        'sku' => 'SKU',
        'name' => 'nombre',
        'description' => 'descripción',
        'min_stock' => 'stock mínimo',
        'cost_price' => 'precio de costo',
        'sale_price' => 'precio de venta',
        'expiration_date' => 'fecha de vencimiento',
        'product_id' => 'producto',
        'type' => 'tipo de movimiento',
        'quantity' => 'cantidad',
        'reason' => 'motivo',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'current_password' => 'contraseña actual',
    ],
    'stock_movement' => [
        'quantity_positive' => 'La cantidad debe ser mayor que cero.',
        'insufficient_stock' => 'No hay existencias suficientes para registrar este movimiento.',
        'cancel_below_zero' => 'No se puede cancelar el movimiento porque dejaría el stock por debajo de cero.',
        'product_unavailable' => 'No se pueden registrar movimientos para un producto eliminado.',
    ],
];
