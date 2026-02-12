<?php

/**
 * Console command for interactive user management operations.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Console\Commands
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/Ometra-Core/mx.ometra.caronte-client Documentation
 */

namespace Ometra\Caronte\Console\Commands;

use Ometra\Caronte\Api\RoleApiClient;
use Illuminate\Console\Command;

class ManagementUsers extends Command
{
    protected $signature = 'caronte-client:management-users';
    protected $description = 'Manages users within the application';

    public function handle()
    {
        $mainOptions = [
            '0' => 'Crear nuevo usuario',
            '1' => 'Gestionar un usuario existente',
            '2' => 'Enlazar roles a usuarios',
            '3' => 'Salir',
        ];
        $optionsRoles = [
            '0' => 'Editar usuario',
            '1' => 'Eliminar roles asociados al usuario',
            '2' => 'Mostrar roles en la aplicación',
            '3' => 'Salir',
        ];
        do {
            $selectedOption = $this->choice(
                'Selecciona una opción:',
                array_values($mainOptions)
            );
            $option = array_search($selectedOption, $mainOptions);
            switch ($option) {
                case '0':
                    $this->call('caronte-client:create-user');
                    break;
                case '1':
                    $this->info('A continuación se mostrará la lista de usuarios que puedes gestionar...');

                    $response = RoleApiClient::showUsers(paramSearch: '', usersApp: true);
                    $users = json_decode($response['data'], true);

                    if (empty($users)) {
                        $this->warn("No se encontraron usuarios que gestionar en esta aplicación");
                        break;
                    }

                    $options = [];
                    $lookupMap = [];

                    foreach ($users as $user) {
                        $key = "{$user['name']} - (uri_user: {$user['uri_user']})";
                        $options[] = $key;
                        $lookupMap[$key] = $user['uri_user'];
                    }

                    $selectedOption = $this->choice(
                        '¿Qué usuario estás buscando?',
                        $options
                    );
                    $selectedUserId = $lookupMap[$selectedOption];
                    $userSelect = collect($users)->firstWhere('uri_user', $selectedUserId);

                    do {
                        $selectedOptionRoles = $this->choice(
                            'Selecciona una opción:',
                            array_values($optionsRoles)
                        );
                        $optionRole = array_search($selectedOptionRoles, $optionsRoles);
                        switch ($optionRole) {
                            case '0':
                                $this->call('caronte-client:update-user', ['uri_user' => $selectedUserId, 'name_user' => $userSelect['name']]);
                                break;
                            case '1':
                                $this->call('caronte-client:delete-user-roles', ['uri_user' => $selectedUserId, 'name_user' => $userSelect['name']]);
                                break;
                            case '2':
                                $this->call('caronte-client:show-user-roles', ['uri_user' => $selectedUserId]);
                                break;
                            case '3':
                                $this->info('Regresando al menú principal...');
                                break 2;
                            default:
                                $this->error('Opción no válida. Por favor, intenta de nuevo.');
                                break;
                        }
                    } while (true);
                    break;
                case '2':
                    $this->call('caronte-client:attach-roles');
                    break;
                case '3':
                    $this->info('Saliendo del gestor de usuarios...');
                    return 0;
                default:
                    $this->error('Opción no válida. Por favor, intenta de nuevo.');
                    break;
            }
        } while (true);
    }
}
