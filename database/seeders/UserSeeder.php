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
        // create 400 users for testing purposes
        User::factory()->count(400)->create();
    }
}
