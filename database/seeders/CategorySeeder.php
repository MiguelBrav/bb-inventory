<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Higiene personal',
            'Limpieza del hogar',
            'Papel y desechables',
        ] as $name) {
            Category::query()->firstOrCreate(['name' => $name]);
        }
    }
}
