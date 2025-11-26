<?php

namespace Ometra\Caronte\Commands\CrudRoles;

use Ometra\Caronte\AppBound;
use Ometra\Caronte\Commands\SuperCommand;

use function Laravel\Prompts\text;

class UpdateRole extends SuperCommand
{
    protected $signature = 'caronte-client:edit-role {uri_rol}';
    protected $description = 'Update a role within the application';

    public function executeCommand()
    {
        $uri_applicationRole = $this->argument('uri_rol');
        $description = text('Escribe la nueva descripción del rol:');
        $response = AppBound::updateRole(uriApplicationRole: $uri_applicationRole, description: $description);
        if ($response->getStatusCode() !== 200) {
            $this->error("Error al actualizar el rol: " . $response->getContent());
            return 1;
        }
        $this->info("¡Listo! El rol '{$uri_applicationRole}' ha sido actualizado exitosamente.");

        return 0;
    }
}
