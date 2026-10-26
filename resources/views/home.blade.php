@extends('layouts.app')

@section('title', 'CNW Clothing - Thời trang')

@section('content')
<div class="p-5 mb-4 rounded-3 bg-dark text-white">
    <div class="container-fluid py-2">
        <h1 class="display-6 fw-bold">Thời trang cho phong cách của bạn</h1>
        <p class="lead mb-0">Khám phá các sản phẩm mới nhất từ CNW Clothing.</p>
    </div>
</div>
<div class="d-flex flex-wrap gap-2 mb-4">
    <span class="fw-semibold me-2 align-self-center">Danh mục:</span>
    @forelse ($categories as $category)
        <span class="badge text-bg-light border p-2">{{ $category->name }}</span>
    @empty
        <span class="text-muted">Chưa có danh mục</span>
    @endforelse
</div>
<div class="row g-4">
    @forelse ($products as $product)
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <span class="badge text-bg-secondary mb-2">{{ $product->category->name }}</span>
                    <h2 class="h5">{{ $product->name }}</h2>
                    <p class="text-muted small">{{ $product->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }} đ</strong>
                        <span class="small text-muted">Còn {{ $product->stock }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-info">Chưa có sản phẩm nào.</div></div>
    @endforelse
</div>
@endsection
