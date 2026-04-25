<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the products table with sample data.
     * These are typical items you might find at a school canteen.
     */
    public function run(): void
    {
        $catMakanan = \App\Models\Category::create(['name' => 'Makanan']);
        $catMinuman = \App\Models\Category::create(['name' => 'Minuman']);
        $catSnack = \App\Models\Category::create(['name' => 'Cemilan']);

        $products = [
            ['category_id' => $catMakanan->id, 'name' => 'Nasi Goreng',       'cost_price' => 10000, 'price' => 15000, 'stock' => 50],
            ['category_id' => $catMakanan->id, 'name' => 'Mie Goreng',         'cost_price' => 8000, 'price' => 12000, 'stock' => 50],
            ['category_id' => $catMakanan->id, 'name' => 'Nasi Ayam Geprek',   'cost_price' => 12000, 'price' => 18000, 'stock' => 30],
            ['category_id' => $catMakanan->id, 'name' => 'Nasi Ayam Bakar',    'cost_price' => 15000, 'price' => 20000, 'stock' => 25],
            ['category_id' => $catMakanan->id, 'name' => 'Bakso',              'cost_price' => 8000, 'price' => 12000, 'stock' => 40],
            ['category_id' => $catMakanan->id, 'name' => 'Soto Ayam',          'cost_price' => 9000, 'price' => 13000, 'stock' => 35],

            ['category_id' => $catMinuman->id, 'name' => 'Es Teh Manis',       'cost_price' => 2000, 'price' => 5000,  'stock' => 100],
            ['category_id' => $catMinuman->id, 'name' => 'Es Jeruk',           'cost_price' => 3000, 'price' => 6000,  'stock' => 80],
            ['category_id' => $catMinuman->id, 'name' => 'Kopi Susu',          'cost_price' => 5000, 'price' => 10000, 'stock' => 60],
            ['category_id' => $catMinuman->id, 'name' => 'Air Mineral',        'cost_price' => 1000, 'price' => 3000,  'stock' => 200], // Profit Tinggi & Overstock

            ['category_id' => $catSnack->id, 'name' => 'Gorengan (5 pcs)',   'cost_price' => 3000, 'price' => 5000,  'stock' => 100],
            ['category_id' => $catSnack->id, 'name' => 'Roti Bakar',         'cost_price' => 5000, 'price' => 8000,  'stock' => 40],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
