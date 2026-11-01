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
            <tr data-cart-row data-unit-price="{{ $item['unit_price'] }}">
                <td><div class="d-flex align-items-center gap-2">@if($item['product']->image)<img src="{{ $item['product']->image }}" width="52" height="52" class="rounded object-fit-cover" alt="{{ $item['product']->name }}">@endif<strong>{{ $item['product']->name }}</strong></div></td>
                <td>
                    @if($item['sizes']->isNotEmpty())
                        <select name="items[{{ $item['id'] }}][size_id]" class="form-select">
                            @foreach($item['sizes'] as $size)
                                <option value="{{ $size->id }}" data-stock="{{ $size->stock }}" @selected($size->id === $item['size_id']) @disabled($size->stock < 1)>{{ $size->name }}{{ $size->stock < 1 ? ' (Hết hàng)' : '' }}</option>
                            @endforeach
                        </select>
                    @else
                        Không áp dụng
                        <input type="hidden" name="items[{{ $item['id'] }}][size_id]" value="">
                    @endif
                </td>
                <td>{{ number_format($item['unit_price'], 0, ',', '.') }} đ</td>
                <td><input data-quantity name="items[{{ $item['id'] }}][quantity]" class="form-control" type="number" min="1" max="{{ $item['available'] }}" value="{{ $item['quantity'] }}"></td>
                <td data-subtotal>{{ number_format($item['subtotal'], 0, ',', '.') }} đ</td>
                <td><button type="submit" name="remove_id" value="{{ $item['id'] }}" class="btn btn-sm btn-outline-danger">Xóa</button></td>
            </tr>
        @endforeach
        </tbody>
    </table></div></div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div></div>
        <div class="text-end"><div class="fs-5 mb-2">Tổng: <strong data-total class="text-danger">{{ number_format($total, 0, ',', '.') }} đ</strong></div><a href="{{ route('checkout.create') }}" class="btn btn-primary">Tiến hành đặt hàng</a></div>
    </div>
</form>
@else
<div class="alert alert-info">Giỏ hàng đang trống. <a href="{{ route('home') }}">Xem sản phẩm</a></div>
@endif
@endsection
@push('scripts')
<script>
    const cartForm = document.querySelector('form[action="{{ route('cart.update') }}"]');
    if (cartForm) {
        const formatMoney = value => new Intl.NumberFormat('vi-VN').format(value) + ' đ';
        let saveTimer;

        const refreshTotals = row => {
            const quantity = row.querySelector('[data-quantity]');
            const subtotal = Number(row.dataset.unitPrice) * Number(quantity.value || 0);
            row.querySelector('[data-subtotal]').textContent = formatMoney(subtotal);
            document.querySelector('[data-total]').textContent = formatMoney(
                [...document.querySelectorAll('[data-cart-row]')].reduce((total, item) => {
                    return total + Number(item.dataset.unitPrice) * Number(item.querySelector('[data-quantity]').value || 0);
                }, 0)
            );
        };

        const saveCart = () => {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(() => {
                fetch(cartForm.action, {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'},
                    body: new FormData(cartForm)
                }).catch(() => {});
            }, 300);
        };

        cartForm.querySelectorAll('[data-cart-row]').forEach(row => {
            const quantity = row.querySelector('[data-quantity]');
            const size = row.querySelector('select');
            const update = () => {
                if (size) {
                    const selected = size.options[size.selectedIndex];
                    quantity.max = selected.dataset.stock || quantity.max;
                    if (Number(quantity.value) > Number(quantity.max)) {
                        quantity.value = quantity.max;
                    }
                }
                refreshTotals(row);
                saveCart();
            };
            quantity.addEventListener('input', update);
            if (size) size.addEventListener('change', update);
        });
    }
</script>
@endpush
