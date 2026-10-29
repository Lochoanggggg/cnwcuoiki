<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\OrderCancellationService;

class OrderController extends Controller
{
    private const STATUSES = ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'];

    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->filled('status'), fn ($query) => $query->where('status', (string) $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('orders.index', ['orders' => $orders, 'statuses' => self::STATUSES]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('orders.show', ['order' => $order, 'statuses' => self::STATUSES]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
        ]);
        if ($order->status === 'cancelled' && $data['status'] !== 'cancelled') {
            return back()->withErrors(['status' => 'Đơn hàng đã hủy không thể mở lại.']);
        }

        if ($data['status'] === 'cancelled' && $order->status !== 'cancelled') {
            app(OrderCancellationService::class)->cancel($order);
        } else {
            $order->update($data);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    public function cancel(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('success', 'Đơn hàng đã được hủy trước đó.');
        }

        app(OrderCancellationService::class)->cancel($order);

        return redirect()->route('orders.show', $order)->with('success', 'Đã hủy đơn hàng.');
    }
}
