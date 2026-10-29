@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
@php
    $sizes = old('sizes', $product?->sizes?->map(fn ($size) => ['name' => $size->name, 'stock' => $size->stock])->toArray() ?: [['name' => '', 'stock' => 0]]);
    $images = old('image_urls', $product?->images?->pluck('url')->toArray() ?: ['']);
@endphp
<div class="card border-0 shadow-sm mb-3"><div class="card-body">
    <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Tên sản phẩm *</label><input name="name" class="form-control" value="{{ old('name', $product?->name) }}" required></div>
        <div class="col-md-4"><label class="form-label">Danh mục *</label><select name="category_id" class="form-select" required><option value="">-- Chọn danh mục --</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="col-md-8"><label class="form-label">Slug *</label><input name="slug" class="form-control" value="{{ old('slug', $product?->slug) }}" required></div>
        <div class="col-md-4"><label class="form-label">Giá (VNĐ) *</label><input type="number" min="0" step="1000" name="price" class="form-control" value="{{ old('price', $product?->price) }}" required></div>
        <div class="col-12"><label class="form-label">Mô tả</label><textarea name="description" rows="3" class="form-control">{{ old('description', $product?->description) }}</textarea></div>
        <div class="col-12"><div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $product?->is_active ?? true))><label for="is_active" class="form-check-label">Hiển thị sản phẩm</label></div></div>
    </div>
</div></div>
<div class="card border-0 shadow-sm mb-3"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h5 mb-0">Size và tồn kho</h2><button type="button" class="btn btn-sm btn-outline-primary" onclick="addSize()">+ Thêm size</button></div>
    <div id="sizes">@foreach($sizes as $index => $size)<div class="row g-2 mb-2 size-row"><div class="col"><input name="sizes[{{ $index }}][name]" class="form-control" placeholder="Ví dụ: M" value="{{ $size['name'] }}" required></div><div class="col"><input name="sizes[{{ $index }}][stock]" type="number" min="0" class="form-control" placeholder="Tồn kho" value="{{ $size['stock'] }}" required></div><div class="col-auto"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.size-row').remove()">Xóa</button></div></div>@endforeach</div>
</div></div>
<div class="card border-0 shadow-sm mb-3"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h5 mb-0">Ảnh sản phẩm (URL)</h2><button type="button" class="btn btn-sm btn-outline-primary" onclick="addImage()">+ Thêm ảnh</button></div>
    <div id="images">@foreach($images as $index => $url)<div class="input-group mb-2 image-row"><input name="image_urls[{{ $index }}]" type="url" class="form-control" placeholder="https://..." value="{{ $url }}"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.image-row').remove()">Xóa</button></div>@endforeach</div>
</div></div>
<div class="d-flex gap-2"><button class="btn btn-primary">Lưu sản phẩm</button><a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Quay lại</a></div>
@push('scripts')
<script>
let sizeIndex = {{ count($sizes) }}, imageIndex = {{ count($images) }};
function addSize() { document.getElementById('sizes').insertAdjacentHTML('beforeend', `<div class="row g-2 mb-2 size-row"><div class="col"><input name="sizes[${sizeIndex}][name]" class="form-control" placeholder="Ví dụ: M" required></div><div class="col"><input name="sizes[${sizeIndex}][stock]" type="number" min="0" class="form-control" placeholder="Tồn kho" value="0" required></div><div class="col-auto"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.size-row').remove()">Xóa</button></div></div>`); sizeIndex++; }
function addImage() { document.getElementById('images').insertAdjacentHTML('beforeend', `<div class="input-group mb-2 image-row"><input name="image_urls[${imageIndex}]" type="url" class="form-control" placeholder="https://..."><button type="button" class="btn btn-outline-danger" onclick="this.closest('.image-row').remove()">Xóa</button></div>`); imageIndex++; }
</script>
@endpush
