@extends('layouts.app')
@section('title', 'Quản lý đơn hàng - CNW Clothing')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="h3 mb-1">Quản lý đơn hàng</h1><p class="text-muted mb-0">Theo dõi và cập nhật trạng thái đơn hàng.</p></div><form class="d-flex gap-2" method="GET"><select name="status" class="form-select" onchange="this.form.submit()"><option value="">Tất cả trạng thái</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></form></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table align-middle mb-0"><thead class="table-light"><tr><th>Mã đơn</th><th>Khách hàng</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr></thead><tbody>
@forelse($orders as $order)<tr><td><a href="{{ route('orders.show', $order) }}" class="fw-semibold text-decoration-none">{{ $order->order_code }}</a></td><td>{{ $order->shipping_name }}<br><small class="text-muted">{{ $order->user->email }}</small></td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td><td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td><td><span class="badge text-bg-secondary">{{ ucfirst($order->status) }}</span></td><td class="text-end"><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td></tr>
@empty<tr><td colspan="6" class="text-center text-muted py-5">Chưa có đơn hàng.</td></tr>@endforelse
</tbody></table></div></div><div class="mt-3">{{ $orders->links() }}</div>
@endsection
