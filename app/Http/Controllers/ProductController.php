<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(IndexProductRequest $request): View
    {
        $categoryId = $request->validated('category_id');

        $products = Product::query()
            ->with([
                'category' => static function (BelongsTo $category): void {
                    $category->withTrashed();
                },
            ])
            ->when($categoryId, static fn (Builder $query): Builder => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'categoryId'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = Product::query()->create($request->validated());

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product): View
    {
        $product->load([
            'category' => static function (BelongsTo $category): void {
                $category->withTrashed();
            },
            'stockMovements',
        ]);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
