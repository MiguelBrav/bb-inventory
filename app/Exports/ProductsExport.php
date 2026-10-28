<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport extends SafeExportValueBinder implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private readonly ?int $categoryId = null,
    ) {}

    public function query(): Builder
    {
        $categoryId = $this->categoryId;

        return Product::query()
            ->with([
                'category' => static function (BelongsTo $category): void {
                    $category->withTrashed();
                },
            ])
            ->when(
                $categoryId,
                static fn (Builder $query): Builder => $query->where('category_id', $categoryId),
            )
            ->orderByDesc('created_at')
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Producto',
            'Categoría',
            'Descripción',
            'Existencia',
            'Stock mínimo',
            'Precio de venta',
            'Vencimiento',
        ];
    }

    /**
     * @param  Product  $row
     * @return array<int, int|float|string|null>
     */
    public function map(mixed $row): array
    {
        return [
            $row->sku,
            $row->name,
            $row->category?->name ?? 'Sin categoría',
            $row->description,
            $row->stock,
            $row->min_stock,
            (float) $row->sale_price,
            $row->expiration_date?->format('Y-m-d'),
        ];
    }
}
