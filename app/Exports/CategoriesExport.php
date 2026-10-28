<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport extends SafeExportValueBinder implements FromQuery, WithHeadings, WithMapping
{
    public function query(): Builder
    {
        return Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'Categoría',
            'Slug',
            'Productos activos',
            'Fecha de creación',
        ];
    }

    /**
     * @param  Category  $row
     * @return array<int, int|string>
     */
    public function map(mixed $row): array
    {
        return [
            $row->name,
            $row->slug,
            $row->products_count,
            $row->created_at->format('Y-m-d'),
        ];
    }
}
