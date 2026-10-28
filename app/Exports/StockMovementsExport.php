<?php

declare(strict_types=1);

namespace App\Exports;

use App\Enums\StockMovementType;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockMovementsExport extends SafeExportValueBinder implements FromQuery, WithHeadings, WithMapping
{
    public function query(): Builder
    {
        return StockMovement::query()
            ->with([
                'product' => static function (BelongsTo $product): void {
                    $product->withTrashed();
                },
                'creator' => static function (BelongsTo $creator): void {
                    $creator->withTrashed();
                },
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'SKU',
            'Producto',
            'Tipo',
            'Cantidad',
            'Motivo',
            'Usuario',
        ];
    }

    /**
     * @param  StockMovement  $row
     * @return array<int, int|string>
     */
    public function map(mixed $row): array
    {
        return [
            $row->created_at->format('Y-m-d H:i:s'),
            $row->product?->sku ?? '',
            $row->product?->name ?? 'Producto no disponible',
            $row->type === StockMovementType::Entry ? 'Entrada' : 'Salida',
            $row->quantity,
            $row->reason,
            $row->creator?->name ?? 'Usuario no disponible',
        ];
    }
}
