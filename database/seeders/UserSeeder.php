<?php

namespace Database\Seeders;

use App\Definitions\Roles;
use App\Definitions\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'last_name' => 'ecommerce',
            'phone' => '123456789',
            'email' => 'admin@ecommerce.test',
            'status' => UserStatus::ACTIVE->value,
            'password' => Hash::make('123456789'),
            'role_id' => Roles::ADMIN->value,
            'email_verified_at' => now()
        ]);

        User::factory()->count(400)->create();
    }
}
