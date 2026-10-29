<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderCancellationService
{
    public function cancel(Order $order): void
    {
        if ($order->status === 'cancelled') {
            return;
        }

        DB::transaction(function () use ($order): void {
            $order->load('items.product.sizes');

            foreach ($order->items as $item) {
                if (! $item->product) {
                    continue;
                }

                $item->product->increment('stock', $item->quantity);
                if ($item->size_name) {
                    $size = $item->product->sizes->firstWhere('name', $item->size_name);
                    $size?->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
        });
    }
}
