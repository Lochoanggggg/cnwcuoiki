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
                @if($product->image)
                    <img src="{{ $product->image }}" class="card-img-top" style="height: 240px; object-fit: cover;" alt="{{ $product->name }}">
                @endif
                <div class="card-body">
                    <span class="badge text-bg-secondary mb-2">{{ $product->category->name }}</span>
                    <h2 class="h5"><a href="{{ route('products.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a></h2>
                    <p class="text-muted small">{{ $product->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }} đ</strong>
                        <span class="small text-muted">Còn {{ $product->stock }}</span>
                    </div>
                    @if($product->sizes->isNotEmpty())
                        <div class="small text-muted mt-2">Size: {{ $product->sizes->pluck('name')->join(', ') }}</div>
                    @endif
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-dark btn-sm mt-3">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-info">Chưa có sản phẩm nào.</div></div>
    @endforelse
</div>

<section class="mt-5">
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="row g-0">
            <div class="col-lg-5 bg-dark text-white p-4 p-lg-5">
                <h2 class="h3 fw-bold">Thông tin liên hệ</h2>
                <p class="text-white-50 mb-0">CNW Clothing luôn sẵn sàng hỗ trợ bạn.</p>
            </div>
            <div class="col-lg-7 p-4 p-lg-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h3 class="h6 text-muted">Địa chỉ cửa hàng</h3>
                        <p class="mb-0">{{ config('store.address') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="h6 text-muted">Hotline</h3>
                        <p class="mb-0"><a href="tel:{{ str_replace(' ', '', config('store.phone')) }}" class="text-dark text-decoration-none">{{ config('store.phone') }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="h6 text-muted">Email</h3>
                        <p class="mb-0"><a href="mailto:{{ config('store.email') }}" class="text-dark text-decoration-none">{{ config('store.email') }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="h6 text-muted">Giờ mở cửa</h3>
                        <p class="mb-0">{{ config('store.hours') }}</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ config('store.map') }}" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm">Xem bản đồ</a>
                    <a href="{{ config('store.facebook') }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Facebook</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
