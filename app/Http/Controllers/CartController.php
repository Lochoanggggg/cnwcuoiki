<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = $this->items($request);

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

        $cartItem = $request->user()->cartItems()
            ->where('product_id', $product->id)
            ->where('size_id', $size?->id)
            ->first();
        $available = $size?->stock ?? $product->stock;
        $newQuantity = ($cartItem?->quantity ?? 0) + $data['quantity'];
        if ($available < $newQuantity) {
            return back()->withErrors(['quantity' => 'Tổng số lượng trong giỏ vượt quá tồn kho.'])->withInput();
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $request->user()->cartItems()->create([
                'product_id' => $product->id,
                'size_id' => $size?->id,
                'quantity' => $data['quantity'],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.size_id' => ['nullable', 'integer'],
            'remove_id' => ['nullable', 'integer'],
        ]);

        DB::transaction(function () use ($request, $data): void {
            if (! empty($data['remove_id'])) {
                $request->user()->cartItems()->whereKey($data['remove_id'])->delete();
            }

            foreach ($data['items'] ?? [] as $id => $itemData) {
                $cartItem = $request->user()->cartItems()->with(['product.sizes'])->find($id);
                if (! $cartItem || (int) $id === (int) ($data['remove_id'] ?? 0)) {
                    continue;
                }

                $size = $cartItem->product->sizes->firstWhere('id', $itemData['size_id'] ?? null);
                if ($cartItem->product->sizes->isNotEmpty() && ! $size) {
                    abort(422, 'Size sản phẩm không hợp lệ.');
                }
                $available = $size?->stock ?? $cartItem->product->stock;
                if ($itemData['quantity'] > $available) {
                    abort(422, 'Số lượng đặt vượt quá tồn kho.');
                }

                $duplicate = $request->user()->cartItems()
                    ->where('product_id', $cartItem->product_id)
                    ->where('size_id', $size?->id)
                    ->where('id', '<>', $cartItem->id)
                    ->first();
                if ($duplicate) {
                    $duplicate->update(['quantity' => min($available, $duplicate->quantity + $itemData['quantity'])]);
                    $cartItem->delete();
                    continue;
                }

                $cartItem->update([
                    'size_id' => $size?->id,
                    'quantity' => $itemData['quantity'],
                ]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Đã cập nhật giỏ hàng.']);
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroy(Request $request, int $cartItem): RedirectResponse
    {
        $request->user()->cartItems()->whereKey($cartItem)->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    private function items(Request $request): array
    {
        return $request->user()->cartItems()->with(['product.sizes'])->get()
            ->map(function (CartItem $cartItem): ?array {
                $product = $cartItem->product;
                if (! $product || ! $product->is_active) {
                    return null;
                }
                $size = $product->sizes->firstWhere('id', $cartItem->size_id);

                return [
                    'id' => $cartItem->id,
                    'product' => $product,
                    'sizes' => $product->sizes,
                    'size_id' => $cartItem->size_id,
                    'size_name' => $size?->name,
                    'available' => $size?->stock ?? $product->stock,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => (float) $product->price,
                    'subtotal' => (float) $product->price * $cartItem->quantity,
                ];
            })->filter()->values()->all();
    }
}
