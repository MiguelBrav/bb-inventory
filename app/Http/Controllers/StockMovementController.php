<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StockMovementType;
use App\Http\Requests\StoreStockMovementRequest;
use App\Http\Requests\UpdateStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockMovementService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService,
    ) {}

    public function index(): View
    {
        $stockMovements = StockMovement::query()
            ->with([
                'product' => static function (BelongsTo $product): void {
                    $product->withTrashed();
                },
            ])
            ->latest()
            ->paginate(15);

        return view('stock_movements.index', compact('stockMovements'));
    }

    public function create(): View
    {
        $products = Product::query()->orderBy('name')->get();

        return view('stock_movements.create', compact('products'));
    }

    public function store(StoreStockMovementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $movement = $this->stockMovementService->record(
            (int) $data['product_id'],
            StockMovementType::from($data['type']),
            (int) $data['quantity'],
            $data['reason'],
            (int) $request->user()->getKey(),
        );

        return redirect()
            ->route('stock-movements.edit', $movement)
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function edit(StockMovement $stockMovement): View
    {
        $stockMovement->load([
            'product' => static function (BelongsTo $product): void {
                $product->withTrashed()->with([
                    'category' => static function (BelongsTo $category): void {
                        $category->withTrashed();
                    },
                ]);
            },
        ]);
        $products = Product::query()->orderBy('name')->get();

        return view('stock_movements.edit', compact('stockMovement', 'products'));
    }

    public function update(
        UpdateStockMovementRequest $request,
        StockMovement $stockMovement,
    ): RedirectResponse {
        $data = $request->validated();
        $movement = $this->stockMovementService->revise(
            (int) $stockMovement->getKey(),
            (int) ($data['product_id'] ?? $stockMovement->product_id),
            StockMovementType::from($data['type'] ?? $stockMovement->type->value),
            (int) ($data['quantity'] ?? $stockMovement->quantity),
            $data['reason'] ?? $stockMovement->reason,
        );

        return redirect()
            ->route('stock-movements.edit', $movement)
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(StockMovement $stockMovement): RedirectResponse
    {
        $this->stockMovementService->cancel((int) $stockMovement->getKey());

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Movimiento cancelado correctamente.');
    }
}
