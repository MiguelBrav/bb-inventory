<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="category_id">Categoría</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
            <option value="">Sin categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="sku">SKU <span class="text-danger">*</span></label>
        <input class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" type="text"
               value="{{ old('sku', $product->sku ?? '') }}" maxlength="255" required>
        <div class="form-text">Si el SKU ya existe, se añadirá un sufijo automáticamente.</div>
        @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="name">Nombre <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
               value="{{ old('name', $product->name ?? '') }}" maxlength="255" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Descripción</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                  name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="min_stock">Stock mínimo <span class="text-danger">*</span></label>
        <input class="form-control @error('min_stock') is-invalid @enderror" id="min_stock" name="min_stock"
               type="number" min="0" step="1" value="{{ old('min_stock', $product->min_stock ?? 0) }}" required>
        @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="cost_price">Precio de costo <span class="text-danger">*</span></label>
        <input class="form-control @error('cost_price') is-invalid @enderror" id="cost_price" name="cost_price"
               type="number" min="0" max="99999999.99" step="0.01"
               value="{{ old('cost_price', $product->cost_price ?? '') }}" required>
        @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="sale_price">Precio de venta <span class="text-danger">*</span></label>
        <input class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price"
               type="number" min="0" max="99999999.99" step="0.01"
               value="{{ old('sale_price', $product->sale_price ?? '') }}" required>
        @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="expiration_date">Fecha de vencimiento</label>
        <input class="form-control @error('expiration_date') is-invalid @enderror" id="expiration_date"
               name="expiration_date" type="date"
               value="{{ old('expiration_date', isset($product) ? $product->expiration_date?->format('Y-m-d') : '') }}">
        @error('expiration_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
