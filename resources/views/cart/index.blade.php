@extends('layouts.app')
@section('title', 'Giỏ hàng - CNW Clothing')
@section('content')
<h1 class="h3 mb-4">Giỏ hàng</h1>
@if($items)
<form method="POST" action="{{ route('cart.update') }}">
    @csrf @method('PATCH')
    <div class="card border-0 shadow-sm mb-3"><div class="table-responsive"><table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Sản phẩm</th><th>Size</th><th>Đơn giá</th><th style="width: 130px">Số lượng</th><th>Thành tiền</th><th></th></tr></thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td><div class="d-flex align-items-center gap-2">@if($item['product']->image)<img src="{{ $item['product']->image }}" width="52" height="52" class="rounded object-fit-cover" alt="{{ $item['product']->name }}">@endif<strong>{{ $item['product']->name }}</strong></div></td>
                <td>{{ $item['size_name'] ?: 'Không áp dụng' }}</td>
                <td>{{ number_format($item['unit_price'], 0, ',', '.') }} đ</td>
                <td><input type="number" name="quantities[{{ $item['key'] }}]" class="form-control" min="1" max="{{ $item['available'] }}" value="{{ $item['quantity'] }}"></td>
                <td>{{ number_format($item['subtotal'], 0, ',', '.') }} đ</td>
                <td><button type="submit" name="remove_key" value="{{ $item['key'] }}" class="btn btn-sm btn-outline-danger">Xóa</button></td>
            </tr>
        @endforeach
        </tbody>
    </table></div></div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <button class="btn btn-outline-secondary">Cập nhật giỏ hàng</button>
        <div class="text-end"><div class="fs-5 mb-2">Tổng: <strong class="text-danger">{{ number_format($total, 0, ',', '.') }} đ</strong></div><a href="{{ route('checkout.create') }}" class="btn btn-primary">Tiến hành đặt hàng</a></div>
    </div>
</form>
@else
<div class="alert alert-info">Giỏ hàng đang trống. <a href="{{ route('home') }}">Xem sản phẩm</a></div>
@endif
@endsection
