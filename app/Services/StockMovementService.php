<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StockMovementService
{
    public function record(
        int $productId,
        StockMovementType $type,
        int $quantity,
        string $reason,
    ): StockMovement {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use ($productId, $type, $quantity, $reason): StockMovement {
            $product = Product::query()->lockForUpdate()->findOrFail($productId);

            $this->applyEffect($product, $type, $quantity);

            return $product->stockMovements()->create([
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $reason,
            ]);
        }, 3);
    }

    public function revise(
        int $movementId,
        int $productId,
        StockMovementType $type,
        int $quantity,
        string $reason,
    ): StockMovement {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $movementId,
            $productId,
            $type,
            $quantity,
            $reason,
        ): StockMovement {
            $movement = StockMovement::query()
                ->lockForUpdate()
                ->findOrFail($movementId);

            $productIds = array_values(array_unique([
                (int) $movement->product_id,
                $productId,
            ]));
            sort($productIds);

            $products = Product::withTrashed()
                ->whereKey($productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (Product $product): int => (int) $product->getKey());

            $previousProduct = $products->get((int) $movement->product_id);
            $nextProduct = $products->get($productId);

            if (! $previousProduct instanceof Product || ! $nextProduct instanceof Product) {
                throw (new ModelNotFoundException())->setModel(Product::class, $productIds);
            }

            if ($nextProduct->trashed()) {
                throw ValidationException::withMessages([
                    'product_id' => __('validation.stock_movement.product_unavailable', [], 'es'),
                ]);
            }

            $this->applyEffect($previousProduct, $movement->type, $movement->quantity, true);
            $this->applyEffect($nextProduct, $type, $quantity);

            $movement->fill([
                'product_id' => $productId,
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $reason,
            ]);
            $movement->save();

            return $movement;
        }, 3);
    }

    public function cancel(int $movementId): void
    {
        DB::transaction(function () use ($movementId): void {
            $movement = StockMovement::query()
                ->lockForUpdate()
                ->findOrFail($movementId);

            $product = Product::withTrashed()
                ->lockForUpdate()
                ->findOrFail($movement->product_id);

            $this->applyEffect($product, $movement->type, $movement->quantity, true);
            $movement->delete();
        }, 3);
    }

    private function applyEffect(
        Product $product,
        StockMovementType $type,
        int $quantity,
        bool $reverse = false,
    ): void {
        $change = match ($type) {
            StockMovementType::Entry => $quantity,
            StockMovementType::Exit => -$quantity,
        };

        if ($reverse) {
            $change *= -1;
        }

        $nextStock = $product->stock + $change;

        if ($nextStock < 0) {
            throw ValidationException::withMessages([
                'quantity' => __(
                    $reverse
                        ? 'validation.stock_movement.cancel_below_zero'
                        : 'validation.stock_movement.insufficient_stock',
                    [],
                    'es',
                ),
            ]);
        }

        $product->stock = $nextStock;
        $product->save();
    }

    private function validateQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => __('validation.stock_movement.quantity_positive', [], 'es'),
            ]);
        }
    }
}
