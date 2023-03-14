<?php

namespace Database\Seeders;

use App\Definitions\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'role_id' => 1,
            'email_verified_at' => now()
        ]);
    }
}
