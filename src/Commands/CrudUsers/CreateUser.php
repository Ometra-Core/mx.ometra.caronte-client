<?php

namespace Equidna\Caronte\Commands\CrudUsers;

use Equidna\Caronte\AppBound;
use Equidna\Caronte\AppBoundRequest;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;
use function Laravel\Prompts\password;

class CreateUser extends Command
{
    protected $signature = 'caronte-client:create-user';
    protected $description = 'Create Users within the application';

    public function handle()
    {
        $name = text(
            label: 'Escribe el nombre del usuario: ',
            required: true
        );
        $email = text(
            label: 'Escribe el Email del usuario: ',
            required: true,
            validate: ['email' => 'email']
        );
        $password = password(
            label: 'Escribe la contraseña: ',
            required: true
        );
        $response = AppBoundRequest::createUser(name: $name, email: $email, password: $password);
        if ($response->getStatusCode() !== 200) {
            $this->error("Error al crear el usuario: " . $response->getContent());
            return 1;
        }
        $this->info("¡Listo! El usuario '{$name}' ha sido creado exitosamente.");

        return 0;
    }
}
