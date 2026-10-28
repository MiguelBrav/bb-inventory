<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $today = $now->copy()->startOfDay();
        $chartStart = $today->copy()->subDays(29);
        $chartEnd = $now->copy()->endOfDay();
        $weekStart = $now->copy()->startOfWeek();

        $dailyTotals = collect(range(0, 29))
            ->mapWithKeys(static function (int $offset) use ($chartStart): array {
                return [
                    $chartStart->copy()->addDays($offset)->toDateString() => [
                        StockMovementType::Entry->value => 0,
                        StockMovementType::Exit->value => 0,
                    ],
                ];
            })
            ->all();

        $movementTotals = StockMovement::query()
            ->whereBetween('created_at', [$chartStart, $chartEnd])
            ->whereHas('product')
            ->selectRaw('DATE(created_at) as movement_date, type, SUM(quantity) as quantity')
            ->groupBy('movement_date', 'type')
            ->get();

        foreach ($movementTotals as $movementTotal) {
            $dailyTotals[$movementTotal->movement_date][$movementTotal->type->value] =
                (int) $movementTotal->quantity;
        }

        $series = [
            'labels' => array_keys($dailyTotals),
        ];

        $series['labels'] = collect($series['labels'])
            ->map(static fn (string $date): string => Carbon::parse($date)->format('d/m'))
            ->all();
        $series['entries'] = array_map(
            static fn (array $totals): int => $totals[StockMovementType::Entry->value],
            $dailyTotals,
        );
        $series['exits'] = array_map(
            static fn (array $totals): int => $totals[StockMovementType::Exit->value],
            $dailyTotals,
        );

        $lowStockProducts = Product::query()
            ->lowStock()
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(8)
            ->get();

        $recentMovements = StockMovement::query()
            ->with([
                'product',
                'creator' => static function (BelongsTo $creator): void {
                    $creator->select(['id', 'name']);
                },
            ])
            ->whereHas('product')
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', [
            'activeProductCount' => Product::query()->count(),
            'lowStockCount' => Product::query()->lowStock()->count(),
            'outOfStockCount' => Product::query()->where('stock', 0)->count(),
            'inventoryValue' => number_format(
                (float) Product::query()
                    ->selectRaw('COALESCE(SUM(cost_price * stock), 0) as total')
                    ->value('total'),
                2,
                ',',
                '.',
            ),
            'weeklyMovementCount' => StockMovement::query()
                ->whereBetween('created_at', [$weekStart, $now])
                ->whereHas('product')
                ->count(),
            'lowStockProducts' => $lowStockProducts,
            'recentMovements' => $recentMovements,
            'series' => $series,
        ]);
    }
}
