<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('cart', []) === []) {
            return redirect()->route('home')->withErrors(['cart' => 'Giỏ hàng đang trống.']);
        }

        return view('checkout.create', [
            'user' => $request->user(),
            'cartItems' => $this->cartItems($request),
            'total' => collect($this->cartItems($request))->sum('subtotal'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);
        $cart = $request->session()->get('cart', []);
        if ($cart === []) {
            return redirect()->route('home')->withErrors(['cart' => 'Giỏ hàng đang trống.']);
        }

        $order = DB::transaction(function () use ($cart, $data, $request): Order {
            $total = 0;
            $items = [];
            foreach ($cart as $cartItem) {
                $product = Product::query()->lockForUpdate()->find($cartItem['product_id']);
                $size = $cartItem['size_id']
                    ? ProductSize::query()->lockForUpdate()->where('product_id', $product?->id)->find($cartItem['size_id'])
                    : null;
                $available = $size?->stock ?? $product?->stock ?? 0;

                if (! $product || ! $product->is_active || ($cartItem['size_id'] && ! $size) || $available < $cartItem['quantity']) {
                    abort(422, 'Một sản phẩm trong giỏ không còn đủ tồn kho.');
                }

                $subtotal = (float) $product->price * $cartItem['quantity'];
                $total += $subtotal;
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'size_name' => $size?->name,
                    'quantity' => $cartItem['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ];
                if ($size) {
                    $size->decrement('stock', $cartItem['quantity']);
                }
                $product->decrement('stock', $cartItem['quantity']);
            }

            $order = $request->user()->orders()->create([
                'order_code' => 'CNW-' . now()->format('ymdHis') . '-' . Str::upper(Str::random(4)),
                'status' => 'pending',
                'total_amount' => $total,
                ...$data,
            ]);
            $order->items()->createMany($items);

            return $order;
        });

        $request->session()->forget('cart');

        return redirect()->route('customer.orders.show', $order)->with('success', 'Đặt hàng thành công.');
    }

    private function cartItems(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::with('sizes')->whereIn('id', collect($cart)->pluck('product_id'))->get()->keyBy('id');

        return collect($cart)->map(function (array $item) use ($products): ?array {
            $product = $products->get($item['product_id']);
            if (! $product || ! $product->is_active) {
                return null;
            }
            $size = $product->sizes->firstWhere('id', $item['size_id'] ?? null);
            return [
                'product' => $product,
                'size_name' => $size?->name,
                'quantity' => $item['quantity'],
                'unit_price' => (float) $product->price,
                'subtotal' => (float) $product->price * $item['quantity'],
            ];
        })->filter()->values()->all();
    }
}
