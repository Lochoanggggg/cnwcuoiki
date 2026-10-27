<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'products' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->latest()
                ->get(),
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
