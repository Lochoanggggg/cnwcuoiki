@extends('layouts.app')
@section('title', 'Đơn hàng của tôi - CNW Clothing')
@section('content')
<h1 class="h3 mb-4">Đơn hàng của tôi</h1>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table align-middle mb-0"><thead class="table-light"><tr><th>Mã đơn</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr></thead><tbody>
@forelse($orders as $order)<tr><td class="fw-semibold">{{ $order->order_code }}</td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td><td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td><td><span class="badge text-bg-secondary">{{ ucfirst($order->status) }}</span></td><td><a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a></td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-5">Bạn chưa có đơn hàng nào.</td></tr>@endforelse
</tbody></table></div></div><div class="mt-3">{{ $orders->links() }}</div>
@endsection
