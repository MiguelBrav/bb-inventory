<div>
    <label class="form-label" for="name">Nombre <span class="text-danger">*</span></label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
           value="{{ old('name', $category->name ?? '') }}" maxlength="255" required autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
