<?php

namespace Equidna\Caronte\Commands;

use Equidna\Caronte\AppBound;
use Equidna\Caronte\AppBoundRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

use function Laravel\Prompts\select;
use function Laravel\Prompts\search;
use function Laravel\Prompts\pause;

class ManagementUsers extends Command
{
    protected $signature = 'caronte-client:management-users';
    protected $description = 'Manages users within the application';

    public function handle()
    {
        $mainOptions = [
            '0' => 'Crear nuevo usuario',
            '1' => 'Gestionar un usuario existente',
            '2' => 'Mostrar a los usarios y sus roles en la aplicación',
            '3' => 'Salir',
        ];
        $optionsRoles = [
            '0' => 'Editar usuario',
            '1' => 'Eliminar usuario',
            '2' => 'Salir',
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
                    do {
                        pause('A continuación se mostrará la lista de usuarios que puedes gestionar. Pulsa Enter para continuar...');

                        $response = AppBoundRequest::showUsers(paramSearch: '');
                        $response = $response->getData(true);
                        $users = $response['data'] ?? [];
                        $users = json_decode($users, true);

                        if (empty($users)) {
                            $this->error("No se encontraron usuarios'");
                            return 1;
                        }
                        $usersApp = [];
                        $usersApp = collect($users)->map(function ($user) {
                            dd($user);
                            if ($user['app_id'] === AppBound::getAppId()) {
                                return $user;
                            }
                        })->toArray();
                        dd($usersApp);

                        if (empty($usersApp)) {
                            $this->error("No hay usuarios en esta aplicación.");
                            return 1;
                        }

                        $options = [];
                        $lookupMap = [];

                        foreach ($usersApp as $user) {
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



                        $selectedOptionRoles = select(
                            label: 'Selecciona una opción:',
                            options: array_values($optionsRoles)
                        );
                        $optionRole = array_search($selectedOptionRoles, $optionsRoles);
                        switch ($optionRole) {
                            case '0':
                                $this->call('caronte-client:edit-role', ['uri_rol' => $uriRol]);
                                break;
                            case '1':
                                $this->call('caronte-client:delete-role', ['uri_rol' => $uriRol]);
                                break;
                            case '2':
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
                    $this->call('caronte-client:show-roles');
                    break;
                case '4':
                    $this->info('Saliendo del gestor de roles...');
                    return 0;
                default:
                    $this->error('Opción no válida. Por favor, intenta de nuevo.');
                    break;
            }
        } while (true);
        $response = AppBound::showRoles();
        $response = $response->getData(true);
        $roles = $response['data'] ?? [];
        $roles = json_decode($roles, true);
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
