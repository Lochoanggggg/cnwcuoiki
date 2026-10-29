@extends('layouts.app')
@section('title', 'Đặt hàng - CNW Clothing')
@section('content')
<h1 class="h3 mb-4">Thông tin đặt hàng</h1>
<div class="row g-4">
    <div class="col-lg-7">
        <form method="POST" action="{{ route('checkout.store') }}" class="card border-0 shadow-sm"><div class="card-body">
            @csrf
            @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <div class="mb-3"><label class="form-label">Họ tên người nhận *</label><input name="shipping_name" class="form-control" value="{{ old('shipping_name', $user->name) }}" required></div>
            <div class="mb-3"><label class="form-label">Số điện thoại *</label><input name="shipping_phone" class="form-control" value="{{ old('shipping_phone', $user->phone) }}" required></div>
            <div class="mb-3"><label class="form-label">Địa chỉ giao hàng *</label><textarea name="shipping_address" class="form-control" rows="3" required>{{ old('shipping_address', $user->address) }}</textarea></div>
            <div class="mb-3"><label class="form-label">Ghi chú</label><textarea name="note" class="form-control" rows="2" placeholder="Ghi chú cho đơn hàng...">{{ old('note') }}</textarea></div>
            <button class="btn btn-primary">Xác nhận đặt hàng</button>
        </div></form>
    </div>
    <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body"><h2 class="h5">Đơn hàng</h2>@foreach($cartItems as $item)<div class="d-flex justify-content-between border-bottom py-2"><span>{{ $item['product']->name }} @if($item['size_name'])<small class="text-muted">({{ $item['size_name'] }})</small>@endif × {{ $item['quantity'] }}</span><strong>{{ number_format($item['subtotal'], 0, ',', '.') }} đ</strong></div>@endforeach<div class="d-flex justify-content-between pt-3 fs-5"><strong>Tổng cộng</strong><strong class="text-danger">{{ number_format($total, 0, ',', '.') }} đ</strong></div></div></div></div>
</div>
@endsection
