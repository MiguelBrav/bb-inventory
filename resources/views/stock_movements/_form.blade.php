<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="product_id">Producto <span class="text-danger">*</span></label>
            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                <option value="">Selecciona un producto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        @selected((string) old('product_id', $stockMovement?->product_id ?? request('product_id', '')) === (string) $product->id)>
                        {{ $product->sku }} — {{ $product->name }} (stock: {{ $product->stock }})
                    </option>
                @endforeach
            </select>
            @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="type">Tipo de movimiento <span class="text-danger">*</span></label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="entry" @selected(old('type', $stockMovement?->type->value ?? 'entry') === 'entry')>Entrada</option>
                <option value="exit" @selected(old('type', $stockMovement?->type->value ?? 'entry') === 'exit')>Salida</option>
            </select>
            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="quantity">Cantidad <span class="text-danger">*</span></label>
            <input class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity"
                   type="number" min="1" step="1" value="{{ old('quantity', $stockMovement?->quantity ?? '') }}" required>
            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="reason">Motivo <span class="text-danger">*</span></label>
            <input class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" type="text"
                   value="{{ old('reason', $stockMovement?->reason ?? '') }}" maxlength="255" required>
            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-primary" type="submit">{{ $formMethod === 'PUT' ? 'Guardar cambios' : 'Registrar movimiento' }}</button>
        <a class="btn btn-outline-secondary" href="{{ $cancelRoute }}">Cancelar</a>
    </div>
</form>
