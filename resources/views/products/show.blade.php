@extends('layouts.app')

@section('title', $product->name . ' - CNW Clothing')

@section('content')
<div class="mb-3">
    <a href="{{ route('home') }}" class="text-decoration-none">← Tiếp tục xem sản phẩm</a>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="row g-0">
        <div class="col-lg-6 bg-light">
            @if($product->images->isNotEmpty())
                <div id="productImages" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner">
                        @foreach($product->images as $image)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ $image->url }}" class="d-block w-100" style="height: 520px; object-fit: cover;" alt="{{ $product->name }} - ảnh {{ $loop->iteration }}">
                            </div>
                        @endforeach
                    </div>
                    @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span><span class="visually-hidden">Ảnh trước</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span><span class="visually-hidden">Ảnh sau</span>
                        </button>
                    @endif
                </div>
            @elseif($product->image)
                <img src="{{ $product->image }}" class="w-100" style="height: 520px; object-fit: cover;" alt="{{ $product->name }}">
            @else
                <div class="d-flex align-items-center justify-content-center text-muted" style="height: 520px;">Chưa có hình ảnh</div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="p-4 p-lg-5">
                <span class="badge text-bg-secondary mb-3">{{ $product->category->name }}</span>
                <h1 class="display-6 fw-bold mb-3">{{ $product->name }}</h1>
                <div class="fs-3 fw-bold text-danger mb-3">{{ number_format($product->price, 0, ',', '.') }} đ</div>

                <div class="mb-4">
                    <span class="text-muted">Tình trạng:</span>
                    <strong class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                    </strong>
                    <span class="text-muted">({{ $product->stock }} sản phẩm)</span>
                </div>

                @if($product->sizes->isNotEmpty())
                    <h2 class="h6 fw-bold">Kích thước</h2>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($product->sizes as $size)
                            <span class="border rounded px-3 py-2 {{ $size->stock > 0 ? '' : 'text-muted bg-light' }}">
                                {{ $size->name }}
                                <small class="d-block text-muted">{{ $size->stock > 0 ? 'Còn ' . $size->stock : 'Hết hàng' }}</small>
                            </span>
                        @endforeach
                    </div>
                @endif

                @auth
                    @can('customer')
                        @if($product->stock > 0)
                            @if($errors->any())
                                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                            @endif
                            <form method="POST" action="{{ route('cart.store', $product) }}" class="row g-2 align-items-end">
                                @csrf
                                @if($product->sizes->isNotEmpty())
                                    <div class="col-7">
                                        <label for="size_id" class="form-label fw-semibold">Chọn size</label>
                                        <select name="size_id" id="size_id" class="form-select" required>
                                            <option value="">-- Chọn size --</option>
                                            @foreach($product->sizes as $size)
                                                <option value="{{ $size->id }}" @disabled($size->stock < 1)>{{ $size->name }}{{ $size->stock < 1 ? ' (Hết hàng)' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="{{ $product->sizes->isNotEmpty() ? 'col-5' : 'col-6' }}">
                                    <label for="quantity" class="form-label fw-semibold">Số lượng</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" max="99" value="{{ old('quantity', 1) }}" required>
                                </div>
                                <div class="col-12"><button class="btn btn-dark w-100">Thêm vào giỏ hàng</button></div>
                            </form>
                        @endif
                    @endcan
                @endauth

                <hr>
                <h2 class="h5 mt-4">Mô tả sản phẩm</h2>
                <div class="text-muted" style="white-space: pre-line;">{{ $product->description ?: 'Sản phẩm chưa có mô tả.' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
