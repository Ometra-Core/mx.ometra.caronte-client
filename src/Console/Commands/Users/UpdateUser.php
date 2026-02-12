<?php

/**
 * Console command to update user information by URI and name.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Console\Commands\Users
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/Ometra-Core/mx.ometra.caronte-client Documentation
 */

namespace Ometra\Caronte\Console\Commands\Users;

use Ometra\Caronte\Api\RoleApiClient;
use Illuminate\Console\Command;

class UpdateUser extends Command
{
    protected $signature = 'caronte-client:update-user {uri_user} {name_user}';
    protected $description = 'Update a user within the application';

    public function handle()
    {
        $uri_user = $this->argument('uri_user');
        $name_user = $this->argument('name_user');
        $newName = $this->ask('Escribe el nuevo nombre del usuario:');
        $response = RoleApiClient::updateUser(uri_user: $uri_user, name: $newName);
        if (!$response['success']) {
            $this->error("Error al actualizar el usuario: " . $response['error']);
            return 1;
        }
        $this->info("¡Listo! El usuario '{$name_user}' ha sido actualizado exitosamente.");

        return 0;
    }
}
