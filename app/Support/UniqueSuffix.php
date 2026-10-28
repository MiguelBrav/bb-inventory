<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

final class UniqueSuffix
{
    public static function for(Model $model, string $attribute, string $value): string
    {
        if ($value === '') {
            return $value;
        }

        if (! self::exists($model, $attribute, $value)) {
            return $value;
        }

        for ($suffix = 2; ; $suffix++) {
            $candidate = "{$value}-{$suffix}";

            if (! self::exists($model, $attribute, $candidate)) {
                return $candidate;
            }
        }
    }

    private static function exists(Model $model, string $attribute, string $value): bool
    {
        $query = $model->newQuery()->withTrashed();

        if ($model->exists) {
            $query->where($model->getKeyName(), '<>', $model->getKey());
        }

        return $query->where($attribute, $value)->exists();
    }
}
