<?php

namespace Database\Seeders;

use App\Domain\Categories\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RoleSeeder::class]);

        Category::factory()->create(['name' => 'General']);

        if (env('APP_ENV') !== 'production') {
            $this->call([UserSeeder::class, CategorySeeder::class, ProductSeeder::class]);
        }
    }
}
