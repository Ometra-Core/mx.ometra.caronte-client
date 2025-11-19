<?php

namespace Equidna\Caronte\Commands;

use Equidna\Caronte\AppBound;
use Equidna\Caronte\Commands\SuperCommand;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\search;

class ManagementRoles extends SuperCommand
{
    protected $signature = 'caronte-client:management-roles';
    protected $description = 'Manages roles within the application';

    public function executeCommand()
    {
        $mainOptions = [
            '0' => 'Crear nuevo rol',
            '1' => 'Gestionar un rol existente',
            '2' => 'Enlzar roles a usuarios',
            '3' => 'Ver roles existentes',
            '4' => 'Salir',
        ];
        $optionsRoles = [
            '0' => 'Editar rol',
            '1' => 'Eliminar rol',
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
                    $this->call('caronte-client:create-role');
                    break;
                case '1':
                    do {
                        AppBound::initializeSettings();
                        $response = AppBound::showRoles();
                        $response = $response->getData(true);
                        $roles = $response['data'] ?? [];
                        $roles = json_decode($roles, true);
                        if (empty($roles)) {
                            $this->warn("No hay roles registrados.");
                            return 0;
                        }

                        $choices = [];
                        $choicesValues = [];
                        foreach ($roles as $rol) {
                            $label = "{$rol['name']} - {$rol['description']}";
                            $choices[$label] = $rol['uri_applicationRole'];
                            $choicesValues[] = $label;
                        }


                        $selectedLabel = search(
                            label: 'Escribe el rol que quieres gestionar',
                            options: fn(string $value) => strlen($value) > 0
                                ? collect($choicesValues)
                                ->filter(fn($choiceValue) => Str::contains($choiceValue, $value, ignoreCase: true))
                                ->values()
                                ->all()
                                : []
                        );

                        $selectedUri = $choices[$selectedLabel];
                        $selectedRol = collect($roles)->firstWhere('uri_applicationRole', $selectedUri);
                        $uriRol = $selectedRol['uri_applicationRole'] ?? null;

                        if (!$selectedRol) {
                            $this->error("Rol no encontrado.");
                            return 1;
                        }

                        $this->info("Has seleccionado: {$selectedRol['name']}");


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
