<?php

namespace Equidna\Caronte\Commands\CrudUsers;

use Equidna\Caronte\AppBoundRequest;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;

class DeleteUser extends Command
{
    protected $signature = 'caronte-client:delete-user {uri_rol} {name_user}';
    protected $description = 'Delete a user within the application';

    public function executeCommand()
    {
        $uri_user = $this->argument('uri_user');
        $name = $this->argument('name_user');
        if (confirm("Seguro que deseas eliminar al usuario: {$name}?")) {
            $response = AppBoundRequest::deleteUser(uri_user: $uri_user);
            if ($response->getStatusCode() !== 200) {
                $this->error("Error al eliminar al usuario: " . $response->getContent());
                return 1;
            }
            $this->info("¡Listo! El usuario '{$name}' ha sido eliminado exitosamente.");
        } else {
            $this->info('Operación cancelada.');
        }
        return 0;
    }
}
