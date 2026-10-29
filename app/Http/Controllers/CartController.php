<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $this->cart($request);
        $items = $this->items($cart);

        return view('cart.index', [
            'items' => $items,
            'total' => collect($items)->sum(fn (array $item): float => $item['subtotal']),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);
        $product->load('sizes');

        $data = $request->validate([
            'size_id' => ['nullable', 'integer'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);
        $size = $product->sizes->firstWhere('id', $data['size_id'] ?? null);
        if ($product->sizes->isNotEmpty() && ! $size) {
            return back()->withErrors(['size_id' => 'Vui lòng chọn size hợp lệ.'])->withInput();
        }

        $available = $size?->stock ?? $product->stock;
        if ($available < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Số lượng đặt vượt quá tồn kho.'])->withInput();
        }

        $key = $product->id . '-' . ($size?->id ?? 'default');
        $cart = $this->cart($request);
        $newQuantity = ($cart[$key]['quantity'] ?? 0) + $data['quantity'];
        if ($newQuantity > $available) {
            return back()->withErrors(['quantity' => 'Tổng số lượng trong giỏ vượt quá tồn kho.'])->withInput();
        }

        $cart[$key] = [
            'product_id' => $product->id,
            'size_id' => $size?->id,
            'size_name' => $size?->name,
            'quantity' => $newQuantity,
        ];
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quantities' => ['nullable', 'array'],
            'quantities.*' => ['required', 'integer', 'min:1', 'max:99'],
            'remove_key' => ['nullable', 'string'],
        ]);
        $cart = $this->cart($request);

        if (! empty($data['remove_key'])) {
            unset($cart[$data['remove_key']]);
        }
        foreach ($data['quantities'] ?? [] as $key => $quantity) {
            if (isset($cart[$key]) && $key !== ($data['remove_key'] ?? null)) {
                $cart[$key]['quantity'] = $quantity;
            }
        }
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroy(Request $request, string $key): RedirectResponse
    {
        $cart = $this->cart($request);
        unset($cart[$key]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    private function cart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }

    private function items(array $cart): array
    {
        $products = Product::with('sizes')->whereIn('id', collect($cart)->pluck('product_id'))->get()->keyBy('id');

        return collect($cart)->map(function (array $cartItem, string $key) use ($products): ?array {
            $product = $products->get($cartItem['product_id']);
            if (! $product || ! $product->is_active) {
                return null;
            }
            $size = $product->sizes->firstWhere('id', $cartItem['size_id'] ?? null);
            $price = (float) $product->price;

            return [
                'key' => $key,
                'product' => $product,
                'size_name' => $size?->name,
                'available' => $size?->stock ?? $product->stock,
                'quantity' => $cartItem['quantity'],
                'unit_price' => $price,
                'subtotal' => $price * $cartItem['quantity'],
            ];
        })->filter()->values()->all();
    }
}
