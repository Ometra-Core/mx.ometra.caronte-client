<?php

namespace Ometra\Caronte\Commands\CrudRoles;

use Ometra\Caronte\AppBound;
use Ometra\Caronte\Commands\SuperCommand;

use function Laravel\Prompts\confirm;

class DeleteRole extends SuperCommand
{
    protected $signature = 'caronte-client:delete-role {uri_rol}';
    protected $description = 'Delete a role within the application';

    public function executeCommand()
    {
        $uri_applicationRole = $this->argument('uri_rol');
        if (confirm("Seguro que deseas eliminar el rol: {$uri_applicationRole}?")) {
            $response = AppBound::deleteRole(uriApplicationRole: $uri_applicationRole);
            $this->info("¡Listo! El rol '{$uri_applicationRole}' ha sido eliminado exitosamente.");
        } else {
            $this->info('Operación cancelada.');
        }
        return 0;
    }
}
