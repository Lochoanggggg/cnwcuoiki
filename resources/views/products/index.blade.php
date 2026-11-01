@extends('layouts.app')

@section('title', 'Quản lý sản phẩm - CNW Clothing')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Quản lý sản phẩm</h1>
        <p class="text-muted mb-0">Quản lý thông tin, size, tồn kho và hình ảnh sản phẩm.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('products.create') }}">+ Thêm sản phẩm</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Size / tồn kho</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)<img src="{{ $product->image }}" alt="{{ $product->name }}" width="52" height="52" class="rounded object-fit-cover me-2">@endif
                        <strong>{{ $product->name }}</strong><br><small class="text-muted">{{ $product->slug }}</small>
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                    <td>
                        <div class="small">{{ $product->sizes->pluck('name')->join(', ') ?: 'Chưa có size' }}</div>
                        <span class="text-muted small">{{ $product->stock }} sản phẩm</span>
                    </td>
                    <td><span class="badge text-bg-{{ $product->is_active ? 'success' : 'secondary' }}">{{ $product->is_active ? 'Đang bán' : 'Ẩn' }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                        <form class="d-inline" method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Xóa sản phẩm này?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-5">Chưa có sản phẩm.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
