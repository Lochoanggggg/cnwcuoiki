<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\OrderCancellationService;

class CustomerOrderController extends Controller
{
    public function index(Request $request): View
    {
        return view('customer-orders.index', [
            'orders' => $request->user()->orders()->latest()->paginate(10),
        ]);
    }

    public function show(Request $request, int $order): View
    {
        $order = $request->user()->orders()->with('items')->findOrFail($order);

        return view('customer-orders.show', compact('order'));
    }

    public function cancel(Request $request, int $order)
    {
        $order = $request->user()->orders()->findOrFail($order);
        if ($order->status !== 'pending') {
            return back()->withErrors(['cancel' => 'Đơn hàng đã được xử lý. Vui lòng liên hệ với chủ shop để được hỗ trợ hủy đơn.']);
        }

        app(OrderCancellationService::class)->cancel($order);

        return redirect()->route('customer.orders.show', $order)->with('success', 'Đã hủy đơn hàng.');
    }
}
