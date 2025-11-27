<?php

namespace Ometra\Caronte\Commands\CrudUsers;

use Ometra\Caronte\AppBound;
use Ometra\Caronte\AppBoundRequest;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;
use function Laravel\Prompts\password;
use function Laravel\Prompts\info;

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
        $password = '';
        $password_confirmaiton = '';
        do {
            $password = password(
                label: 'Escribe la contraseña: ',
                required: true
            );
            $password_confirmaiton = password(
                label: 'Confirma la contraseña: ',
                required: true
            );
            if ($password != $password_confirmaiton) {
                info('las contraseñas no coinciden');
            }
        } while ($password != $password_confirmaiton);

        $response = AppBoundRequest::createUser(name: $name, email: $email, password: $password, password_confirmation: $password_confirmaiton);
        if (!$response['success']) {
            $this->error("Error al crear el usuario: " . $response['error']);
            return 1;
        }
        $this->info("¡Listo! El usuario '{$name}' ha sido creado exitosamente.");

        return 0;
    }
}
