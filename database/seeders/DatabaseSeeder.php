<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default Admin user (Owner)
        \App\Models\User::factory()->create([
            'name' => 'Owner / Admin',
            'email' => 'admin@kasirin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create a default Cashier user
        \App\Models\User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir@kasirin.test',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

        // Seed sample products
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
