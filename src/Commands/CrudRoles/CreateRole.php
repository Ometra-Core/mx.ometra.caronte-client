<?php

namespace Ometra\Caronte\Commands\CrudRoles;

use Ometra\Caronte\AppBound;
use Ometra\Caronte\Commands\SuperCommand;

use function Laravel\Prompts\text;

class CreateRole extends SuperCommand
{
    protected $signature = 'caronte-client:create-role';
    protected $description = 'Create Roles within the application';

    public function executeCommand()
    {
        $name = text('Escribe el nombre del nuevo rol:');
        $description = text('Escribe la descripción del nuevo rol:');
        $response = AppBound::createRole(description: $description, name: $name);
        $this->info("¡Listo! El rol '{$name}' ha sido creado exitosamente.");

        return 0;
    }
}
