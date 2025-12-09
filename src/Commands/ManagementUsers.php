<?php

namespace Ometra\Caronte\Commands;

use Ometra\Caronte\AppBound;
use Ometra\Caronte\AppBoundRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\search;
use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

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
            $selectedOption = select(
                label: 'Selecciona una opción:',
                options: array_values($mainOptions)
            );
            $option = array_search($selectedOption, $mainOptions);
            switch ($option) {
                case '0':
                    $this->call('caronte-client:create-user');
                    break;
                case '1':
                    info('A continuación se mostrará la lista de usuarios que puedes gestionar...');

                    $response = AppBoundRequest::showUsers(paramSearch: '', usersApp: true);
                    $users = json_decode($response['data'], true);

                    if (empty($users)) {
                        warning("No se encontraron usuarios que gestionar en esta aplicación");
                        break;
                    }

                    $options = [];
                    $lookupMap = [];

                    foreach ($users as $user) {
                        $key = "{$user['name']} - (uri_user: {$user['uri_user']})";
                        $options[] = $key;
                        $lookupMap[$key] = $user['uri_user'];
                    }

                    $selectedOption = search(
                        label: '¿Qué usuario estás buscando? (Escribe el nombre o email)',
                        options: fn(string $value) => strlen($value) > 0
                            ? collect($options)
                            ->filter(fn($option) => Str::contains($option, $value, ignoreCase: true))
                            ->values()
                            ->all()
                            : []
                    );
                    $selectedUserId = $lookupMap[$selectedOption];
                    $userSelect = collect($users)->firstWhere('uri_user', $selectedUserId);

                    do {
                        $selectedOptionRoles = select(
                            label: 'Selecciona una opción:',
                            options: array_values($optionsRoles)
                        );
                        $optionRole = array_search($selectedOptionRoles, $optionsRoles);
                        switch ($optionRole) {
                            case '0':
                                $this->call('caronte-client:update-user', ['uri_user' => $selectedUserId, 'name_user' => $userSelect['name']]);
                                break;
                            case '1':
                                $this->call('caronte-client:delete-roles-user', ['uri_user' => $selectedUserId, 'name_user' => $userSelect['name']]);
                                break;
                            case '2':
                                $this->call('caronte-client:users-roles', ['uri_user' => $selectedUserId]);
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
                    $this->call('caronte-client:attached-roles');
                    break;
                case '3':
                    $this->info('Saliendo del gestor de usuarios...');
                    return 0;
                default:
                    $this->error('Opción no válida. Por favor, intenta de nuevo.');
                    break;
            }
        } while (true);
        $response = AppBound::showRoles();
        $roles = json_decode($response, true);
        if (empty($roles)) {
            $this->warn("No hay roles registrados.");
            return 0;
        }

        $choices = [];
        foreach ($roles as $rol) {
            $label = "{$rol['name']} - {$rol['description']}";
            $choices[$label] = $rol['uri_applicationRole'];
        }

        $selectedLabel = $this->choice("Selecciona un rol:", array_keys($choices));
        $selectedUri = $choices[$selectedLabel];
        $selectedRol = collect($roles)->firstWhere('uri_applicationRole', $selectedUri);
        $uriRol = $selectedRol['uri_applicationRole'] ?? null;

        if (!$selectedRol) {
            $this->error("Rol no encontrado.");
            return 1;
        }

        $this->info("Has seleccionado: {$selectedRol['name']}");

        return 0;
    }
}
