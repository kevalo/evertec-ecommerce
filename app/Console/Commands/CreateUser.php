<?php

namespace App\Console\Commands;

use App\Support\Definitions\Roles;
use App\Support\Definitions\UserStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $result = false;
        try {
            $result = DB::table('users')->insert([
                'name' => fake()->name(),
                'last_name' => fake()->lastName(),
                'phone' => fake()->lastName(),
                'email' => $this->ask('Correo electrónico?'),
                'password' => Hash::make($this->secret('Contraseña?')),
                'status' => UserStatus::ACTIVE->value,
                'role_id' => Roles::ADMIN->value,
                'email_verified_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        if ($result) {
            $this->info('Usuario creado!');
        } else {
            $this->error('Error al crear el usuario');
        }
    }
}
