<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::query()->create($request->validated());

        return redirect()
            ->route('categories.edit', $category)
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category): View
    {
        $category->loadCount('products');

        return view('categories.edit', compact('category'));
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category,
    ): RedirectResponse {
        $category->update($request->validated());

        return redirect()
            ->route('categories.edit', $category)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        DB::transaction(function () use ($category): void {
            Product::withTrashed()
                ->where('category_id', $category->getKey())
                ->update(['category_id' => null]);

            $category->delete();
        });

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría eliminada. Los productos quedaron sin categoría.');
    }
}
