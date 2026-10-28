<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::table('products')->whereNull('category_id')->exists()) {
            throw new RuntimeException(
                'Cannot make products.category_id required while products without a category exist.',
            );
        }

        Schema::table('products', function (Blueprint $table): void {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
        });
    }
};
