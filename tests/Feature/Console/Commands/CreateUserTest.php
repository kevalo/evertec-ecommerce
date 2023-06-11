<?php

namespace Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user(): void
    {
        $this->artisan('app:create-user')
            ->expectsQuestion('Correo electrónico?', 'admin@ecommerce.test')
            ->expectsQuestion('Contraseña?', 'password')
            ->expectsOutputToContain('Usuario creado!')
            ->doesntExpectOutputToContain('Error al crear el usuario')
            ->assertExitCode(0);
    }

    public function test_create_user_error(): void
    {
        $this->artisan('app:create-user')
            ->expectsQuestion('Correo electrónico?', 'admin@ecommerce.test')
            ->expectsQuestion('Contraseña?', 'password')
            ->expectsOutputToContain('Usuario creado!')
            ->assertExitCode(0);

        $this->artisan('app:create-user')
            ->expectsQuestion('Correo electrónico?', 'admin@ecommerce.test')
            ->expectsQuestion('Contraseña?', 'password')
            ->expectsOutputToContain('Error al crear el usuario')
            ->assertExitCode(0);
    }
}
