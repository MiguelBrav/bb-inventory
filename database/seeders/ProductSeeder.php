<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StockMovementType;
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
                'Cuidado capilar',
                'Cuidado bucal',
            ])
            ->get()
            ->keyBy('name');

        foreach ($this->products() as $product) {
            $category = $categories->get($product['category']);

            if (! $category instanceof Category) {
                throw new RuntimeException("Seed category [{$product['category']}] is missing.");
            }

            $seededProduct = Product::query()->firstOrCreate(
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

            if ($seededProduct->stock > 0 && ! $seededProduct->stockMovements()->exists()) {
                $seededProduct->stockMovements()->create([
                    'type' => StockMovementType::Entry,
                    'quantity' => $seededProduct->stock,
                    'reason' => 'Carga inicial de datos.',
                ]);
            }
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
            [
                'category' => 'Higiene personal',
                'sku' => 'HIG-003',
                'name' => 'Jabón en barra neutro',
                'description' => 'Jabón en barra para uso diario.',
                'stock' => 18,
                'min_stock' => 6,
                'cost_price' => '12.50',
                'sale_price' => '19.90',
            ],
            [
                'category' => 'Higiene personal',
                'sku' => 'HIG-004',
                'name' => 'Desodorante roll-on',
                'description' => 'Desodorante de uso personal en presentación roll-on.',
                'stock' => 15,
                'min_stock' => 5,
                'cost_price' => '26.00',
                'sale_price' => '38.50',
            ],
            [
                'category' => 'Limpieza del hogar',
                'sku' => 'LIM-003',
                'name' => 'Detergente líquido 1 L',
                'description' => 'Detergente líquido para limpieza de ropa.',
                'stock' => 20,
                'min_stock' => 6,
                'cost_price' => '38.00',
                'sale_price' => '52.00',
            ],
            [
                'category' => 'Limpieza del hogar',
                'sku' => 'LIM-004',
                'name' => 'Limpiador para pisos 1 L',
                'description' => 'Limpiador líquido para pisos y superficies.',
                'stock' => 16,
                'min_stock' => 5,
                'cost_price' => '22.00',
                'sale_price' => '34.90',
            ],
            [
                'category' => 'Papel y desechables',
                'sku' => 'PAP-002',
                'name' => 'Toallas de papel paquete',
                'description' => 'Paquete de toallas de papel absorbentes.',
                'stock' => 14,
                'min_stock' => 5,
                'cost_price' => '24.00',
                'sale_price' => '36.00',
            ],
            [
                'category' => 'Papel y desechables',
                'sku' => 'PAP-003',
                'name' => 'Servilletas paquete',
                'description' => 'Paquete de servilletas desechables.',
                'stock' => 25,
                'min_stock' => 8,
                'cost_price' => '18.50',
                'sale_price' => '27.00',
            ],
            [
                'category' => 'Papel y desechables',
                'sku' => 'PAP-004',
                'name' => 'Pañuelos desechables',
                'description' => 'Paquete de pañuelos faciales desechables.',
                'stock' => 12,
                'min_stock' => 4,
                'cost_price' => '16.00',
                'sale_price' => '24.50',
            ],
            [
                'category' => 'Cuidado capilar',
                'sku' => 'CAP-001',
                'name' => 'Champú uso diario 400 ml',
                'description' => 'Champú para la limpieza diaria del cabello.',
                'stock' => 17,
                'min_stock' => 5,
                'cost_price' => '39.00',
                'sale_price' => '55.00',
            ],
            [
                'category' => 'Cuidado capilar',
                'sku' => 'CAP-002',
                'name' => 'Acondicionador 400 ml',
                'description' => 'Acondicionador para el cuidado del cabello.',
                'stock' => 13,
                'min_stock' => 4,
                'cost_price' => '41.00',
                'sale_price' => '58.00',
            ],
            [
                'category' => 'Cuidado capilar',
                'sku' => 'CAP-003',
                'name' => 'Crema para peinar 300 ml',
                'description' => 'Crema para facilitar el peinado y cuidado del cabello.',
                'stock' => 10,
                'min_stock' => 3,
                'cost_price' => '34.00',
                'sale_price' => '49.90',
            ],
            [
                'category' => 'Cuidado capilar',
                'sku' => 'CAP-004',
                'name' => 'Cepillo para cabello',
                'description' => 'Cepillo de uso personal para el cabello.',
                'stock' => 8,
                'min_stock' => 3,
                'cost_price' => '28.00',
                'sale_price' => '42.00',
            ],
            [
                'category' => 'Cuidado bucal',
                'sku' => 'BUC-001',
                'name' => 'Pasta dental 100 ml',
                'description' => 'Pasta dental para higiene bucal diaria.',
                'stock' => 22,
                'min_stock' => 7,
                'cost_price' => '21.00',
                'sale_price' => '32.00',
            ],
            [
                'category' => 'Cuidado bucal',
                'sku' => 'BUC-002',
                'name' => 'Cepillo dental',
                'description' => 'Cepillo dental de uso personal.',
                'stock' => 19,
                'min_stock' => 6,
                'cost_price' => '13.00',
                'sale_price' => '22.00',
            ],
            [
                'category' => 'Cuidado bucal',
                'sku' => 'BUC-003',
                'name' => 'Enjuague bucal 500 ml',
                'description' => 'Enjuague bucal para complementar la higiene diaria.',
                'stock' => 11,
                'min_stock' => 4,
                'cost_price' => '32.00',
                'sale_price' => '46.00',
            ],
            [
                'category' => 'Cuidado bucal',
                'sku' => 'BUC-004',
                'name' => 'Hilo dental',
                'description' => 'Hilo dental para limpieza interdental.',
                'stock' => 9,
                'min_stock' => 3,
                'cost_price' => '17.00',
                'sale_price' => '26.00',
            ],
        ];
    }
}
