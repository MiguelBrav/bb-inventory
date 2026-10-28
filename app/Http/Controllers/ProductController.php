<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\PdfReportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function export(IndexProductRequest $request): BinaryFileResponse
    {
        $categoryId = $request->validated('category_id');
        $export = new ProductsExport($categoryId === null ? null : (int) $categoryId);

        return Excel::download($export, 'reportes_productos_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPdf(IndexProductRequest $request, PdfReportService $pdfReport): Response
    {
        $categoryId = $request->validated('category_id');

        return $pdfReport->download(
            new ProductsExport($categoryId === null ? null : (int) $categoryId),
            'Reporte de productos',
            'reportes_productos_'.now()->format('Y-m-d').'.pdf',
        );
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
            ->route('products.edit', $product)
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product): View
    {
        $product->load([
            'category' => static function (BelongsTo $category): void {
                $category->withTrashed();
            },
            'stockMovements' => static function (HasMany $stockMovements): void {
                $stockMovements->latest()->limit(5);
            },
        ]);
        $categories = Category::query()->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.edit', $product)
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
