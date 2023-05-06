<?php

namespace Database\Seeders;

use App\Definitions\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(Roles::toArray());
    }
}
