<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\UniqueSuffix;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted(): void
    {
        static::saving(static function (self $category): void {
            if (! $category->isDirty('name') && ! $category->isDirty('slug')) {
                return;
            }

            $slugSource = $category->isDirty('slug') && $category->slug
                ? $category->slug
                : $category->name;

            $category->slug = UniqueSuffix::for(
                $category,
                'slug',
                Str::slug($slugSource),
            );
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
