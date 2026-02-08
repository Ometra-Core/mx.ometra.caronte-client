<?php

/**
 * Console command to create new roles interactively.
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

class CreateRole extends CaronteCommand
{
    protected $signature = 'caronte-client:create-role';
    protected $description = 'Create Roles within the application';

    public function executeCommand()
    {
        $name = $this->ask('Escribe el nombre del nuevo rol:');
        $description = $this->ask('Escribe la descripción del nuevo rol:');
        $response = CaronteRoleManager::createRole($name, $description);
        $this->info("¡Listo! El rol '{$name}' ha sido creado exitosamente.");

        return 0;
    }
}
