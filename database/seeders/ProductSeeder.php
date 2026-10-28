<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()
            ->whereIn('name', [
                'Higiene personal',
                'Limpieza del hogar',
                'Papel y desechables',
            ])
            ->get()
            ->keyBy('name');

        foreach ($this->products() as $product) {
            $category = $categories->get($product['category']);

            if (! $category instanceof Category) {
                throw new RuntimeException("Seed category [{$product['category']}] is missing.");
            }

            Product::query()->firstOrCreate(
                ['name' => $product['name']],
                [
                    'category_id' => $category->getKey(),
                    'sku' => $product['sku'],
                    'description' => $product['description'],
                    'stock' => $product['stock'],
                    'min_stock' => $product['min_stock'],
                    'cost_price' => $product['cost_price'],
                    'sale_price' => $product['sale_price'],
                    'expiration_date' => null,
                ],
            );
        }
    }

    /**
     * @return list<array{
     *     category: string,
     *     sku: string,
     *     name: string,
     *     description: string,
     *     stock: int,
     *     min_stock: int,
     *     cost_price: string,
     *     sale_price: string
     * }>
     */
    private function products(): array
    {
        return [
            [
                'category' => 'Higiene personal',
                'sku' => 'HIG-001',
                'name' => 'Jabón líquido antibacterial',
                'description' => 'Jabón líquido para higiene personal.',
                'stock' => 24,
                'min_stock' => 8,
                'cost_price' => '42.50',
                'sale_price' => '59.90',
            ],
            [
                'category' => 'Higiene personal',
                'sku' => 'HIG-002',
                'name' => 'Gel antibacterial 500 ml',
                'description' => 'Gel antibacterial para manos.',
                'stock' => 4,
                'min_stock' => 10,
                'cost_price' => '35.00',
                'sale_price' => '49.90',
            ],
            [
                'category' => 'Limpieza del hogar',
                'sku' => 'LIM-001',
                'name' => 'Desinfectante multiusos 1 L',
                'description' => 'Limpiador desinfectante para superficies.',
                'stock' => 30,
                'min_stock' => 8,
                'cost_price' => '28.75',
                'sale_price' => '42.00',
            ],
            [
                'category' => 'Limpieza del hogar',
                'sku' => 'LIM-002',
                'name' => 'Cloro 1 L',
                'description' => 'Desinfectante líquido para limpieza general.',
                'stock' => 12,
                'min_stock' => 5,
                'cost_price' => '14.00',
                'sale_price' => '22.50',
            ],
            [
                'category' => 'Papel y desechables',
                'sku' => 'PAP-001',
                'name' => 'Papel higiénico paquete de 4 rollos',
                'description' => 'Paquete de papel higiénico de cuatro rollos.',
                'stock' => 6,
                'min_stock' => 10,
                'cost_price' => '31.00',
                'sale_price' => '45.00',
            ],
        ];
    }
}
