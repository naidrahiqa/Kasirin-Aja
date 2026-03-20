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
            ['category_id' => $catMakanan->id, 'name' => 'Nasi Goreng',       'price' => 15000, 'stock' => 50],
            ['category_id' => $catMakanan->id, 'name' => 'Mie Goreng',         'price' => 12000, 'stock' => 50],
            ['category_id' => $catMakanan->id, 'name' => 'Nasi Ayam Geprek',   'price' => 18000, 'stock' => 30],
            ['category_id' => $catMakanan->id, 'name' => 'Nasi Ayam Bakar',    'price' => 20000, 'stock' => 25],
            ['category_id' => $catMakanan->id, 'name' => 'Bakso',              'price' => 12000, 'stock' => 40],
            ['category_id' => $catMakanan->id, 'name' => 'Soto Ayam',          'price' => 13000, 'stock' => 35],

            ['category_id' => $catMinuman->id, 'name' => 'Es Teh Manis',       'price' => 5000,  'stock' => 100],
            ['category_id' => $catMinuman->id, 'name' => 'Es Jeruk',           'price' => 6000,  'stock' => 80],
            ['category_id' => $catMinuman->id, 'name' => 'Kopi Susu',          'price' => 10000, 'stock' => 60],
            ['category_id' => $catMinuman->id, 'name' => 'Air Mineral',        'price' => 3000,  'stock' => 200],

            ['category_id' => $catSnack->id, 'name' => 'Gorengan (5 pcs)',   'price' => 5000,  'stock' => 100],
            ['category_id' => $catSnack->id, 'name' => 'Roti Bakar',         'price' => 8000,  'stock' => 40],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
