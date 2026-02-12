<?php

/**
 * Console command to delete roles from the system.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Console\Commands\Roles
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/Ometra-Core/mx.ometra.caronte-client Documentation
 */

namespace Ometra\Caronte\Console\Commands\Roles;

use Ometra\Caronte\CaronteRoleManager;
use Ometra\Caronte\Console\Commands\CaronteCommand;

class DeleteRole extends CaronteCommand
{
    protected $signature = 'caronte-client:delete-role {uri_rol}';
    protected $description = 'Delete a role within the application';

    public function executeCommand()
    {
        $uri_applicationRole = $this->argument('uri_rol');
        if ($this->confirm("Seguro que deseas eliminar el rol: {$uri_applicationRole}?")) {
            $response = CaronteRoleManager::deleteRole($uri_applicationRole);
            $this->info("¡Listo! El rol '{$uri_applicationRole}' ha sido eliminado exitosamente.");
        } else {
            $this->info('Operación cancelada.');
        }
        return 0;
    }
}
