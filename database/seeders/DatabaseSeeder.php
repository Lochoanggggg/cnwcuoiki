<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@cnwclothing.test'], [
            'name' => 'Chủ cửa hàng',
            'role' => 'admin',
            'password' => 'password',
        ]);

        User::updateOrCreate(['email' => 'customer@cnwclothing.test'], [
            'name' => 'Khách hàng mẫu',
            'role' => 'customer',
            'password' => 'password',
        ]);

        $categories = [
            ['name' => 'Áo', 'slug' => 'ao', 'description' => 'Áo thời trang nam nữ.'],
            ['name' => 'Quần', 'slug' => 'quan', 'description' => 'Quần cho phong cách hằng ngày.'],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'description' => 'Phụ kiện thời trang.'],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::updateOrCreate(['slug' => $categoryData['slug']], $categoryData);

            if ($category->products()->doesntExist()) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => 'Sản phẩm ' . $category->name . ' cơ bản',
                    'slug' => $category->slug . '-co-ban',
                    'description' => 'Sản phẩm mẫu để bắt đầu cửa hàng.',
                    'price' => 299000,
                    'stock' => 25,
                ]);
            }
        }
    }
}
