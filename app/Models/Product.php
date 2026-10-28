<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\UniqueSuffix;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'stock',
        'min_stock',
        'cost_price',
        'sale_price',
        'expiration_date',
    ];

    protected static function booted(): void
    {
        static::saving(static function (self $product): void {
            if (! $product->isDirty('sku') || ! $product->sku) {
                return;
            }

            $product->sku = UniqueSuffix::for($product, 'sku', trim($product->sku));
        });
    }

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'min_stock' => 'integer',
            'cost_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'expiration_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
