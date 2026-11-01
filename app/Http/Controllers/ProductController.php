<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', [
            'products' => Product::with(['category', 'sizes', 'images'])->latest()->paginate(12),
        ]);
    }

    public function create()
    {
        return view('products.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data): void {
            $product = Product::create([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => collect($data['sizes'] ?? [])->sum('stock'),
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);
            $this->syncDetails($product, $data);
        });

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công.');
    }

    public function edit(Product $product)
    {
        $product->load(['sizes', 'images']);

        return view('products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);

        DB::transaction(function () use ($data, $product): void {
            $product->update([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => collect($data['sizes'] ?? [])->sum('stock'),
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);
            $this->syncDetails($product, $data);
        });

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'sizes' => ['nullable', 'array'],
            'sizes.*.name' => ['required', 'string', 'max:50'],
            'sizes.*.stock' => ['required', 'integer', 'min:0'],
            'image_urls' => ['nullable', 'array'],
            'image_urls.*' => ['nullable', 'url', 'max:2048'],
        ], [
            'slug.unique' => 'Slug sản phẩm đã tồn tại.',
            'sizes.*.name.required' => 'Vui lòng nhập tên size.',
            'sizes.*.stock.required' => 'Vui lòng nhập tồn kho của size.',
            'image_urls.*.url' => 'URL hình ảnh không hợp lệ.',
        ]);
    }

    private function syncDetails(Product $product, array $data): void
    {
        $product->sizes()->delete();
        foreach (array_values($data['sizes'] ?? []) as $size) {
            $product->sizes()->create($size);
        }

        $product->images()->delete();
        foreach (array_values(array_filter($data['image_urls'] ?? [])) as $url) {
            $product->images()->create(['url' => $url]);
        }

        $product->update(['image' => array_values(array_filter($data['image_urls'] ?? []))[0] ?? null]);
    }
}
