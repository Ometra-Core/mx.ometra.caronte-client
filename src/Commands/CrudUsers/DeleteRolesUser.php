<?php

namespace Ometra\Caronte\Commands\CrudUsers;

use Ometra\Caronte\AppBoundRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\search;

class DeleteRolesUser extends Command
{
    protected $signature = 'caronte-client:delete-roles-user {uri_user} {name_user}';
    protected $description = 'Removes app roles that belong to the user';

    public function handle()
    {
        $uri_user = $this->argument('uri_user');
        $name = $this->argument('name_user');
        $mainOptions = [
            '0' => 'Desautorizar todos los roles del usuario',
            '1' => 'Seleccionar roles específicos a remover',
            '2' => 'Salir',
        ];
        $selectedOption = select(
            label: 'Selecciona una opción:',
            options: array_values($mainOptions)
        );
        $option = array_search($selectedOption, $mainOptions);
        $roles = AppBoundRequest::showRolesUser(uri_user: $uri_user);
        $roles = json_decode($roles, true);
        if (empty($roles)) {
            $this->error("No se encontraron roles asociados al usuario");
            return 1;
        }

        switch ($option) {
            case '0':
                if (confirm("Seguro que deseas quitar todos los roles del usuario: {$name}?")) {
                    foreach ($roles as $role) {
                        $uri_applicationRole = $role['uri_applicationRole'];
                        $response = AppBoundRequest::deleteRoleUser(uri_user: $uri_user, uri_applicationRole: $uri_applicationRole);
                        // if ($response->getStatusCode() !== 200) {
                        //     $this->error("Error al eliminar el rol '{$uri_applicationRole}' del usuario: " . $response->getContent());
                        //     return 1;
                        // }
                    }
                } else {
                    $this->info('Operación cancelada.');
                }
                $this->info("¡Listo! Todos los roles del usuario '{$name}' han sido eliminados exitosamente.");
                break;
            case '1':
                $choices = [];
                $choicesValues = [];
                foreach ($roles as $rol) {
                    $label = "{$rol['name']} - {$rol['description']}";
                    $choices[$label] = $rol['uri_applicationRole'];
                    $choicesValues[] = $label;
                }

                $selectedLabel = search(
                    label: 'Escribe el rol que quieres eliminar',
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

                if (confirm("Seguro que deseas eliminar el rol <<{$selectedRol['name']}>> al usuario: {$name}?")) {
                    $response = AppBoundRequest::deleteRoleUser(uri_user: $uri_user, uri_applicationRole: $uriRol);
                    // if ($response->getStatusCode() !== 200) {
                    //     $this->error("Error al eliminar el rol '{$uriRol}' del usuario: " . $response->getContent());
                    //     return 1;
                    // }
                    $this->info("¡Listo! El rol '{$selectedRol['name']}' ha sido eliminado del usuario seleccionado.");
                } else {
                    $this->info('Operación cancelada.');
                }
                break;
            case '2':
                $this->info('Operación cancelada.');
                return 0;
            default:
                $this->error('Opción no válida. Por favor, intenta de nuevo.');
                break;
        }
        return 0;
    }
}
