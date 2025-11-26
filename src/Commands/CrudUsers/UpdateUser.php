<?php

namespace Ometra\Caronte\Commands\CrudUsers;

use Equidna\Caronte\AppBound;
use Equidna\Caronte\AppBoundRequest;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class UpdateUser extends Command
{
    protected $signature = 'caronte-client:update-user {uri_user} {name_user}';
    protected $description = 'Update a user within the application';

    public function handle()
    {
        $uri_user = $this->argument('uri_user');
        $name_user = $this->argument('name_user');
        $newName = text('Escribe el nuevo nombre del usuario:');
        $response = AppBoundRequest::updateUser(uri_user: $uri_user, name: $newName);
        if ($response->getStatusCode() !== 200) {
            $this->error("Error al actualizar el usuario: " . $response->getContent());
            return 1;
        }
        $this->info("¡Listo! El usuario '{$name_user}' ha sido actualizado exitosamente.");

        return 0;
    }
}
