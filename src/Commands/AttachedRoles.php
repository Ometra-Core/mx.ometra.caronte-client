<?php

namespace Ometra\Caronte\Commands;

use Ometra\Caronte\Commands\SuperCommand;
use Ometra\Caronte\AppBoundRequest;
use Ometra\Caronte\AppBound;
use Illuminate\Support\Str;

use function Laravel\Prompts\search;
use function Laravel\Prompts\confirm;

class AttachedRoles extends SuperCommand
{
    protected $signature = 'caronte-client:attached-roles';
    protected $description = 'Associates a specific role with a user';

    public function executeCommand()
    {
        $response = AppBoundRequest::showUsers(paramSearch: '');
        $response = $response->getData(true);
        $users = $response['data'] ?? [];
        $users = json_decode($users, true);

        if (empty($users)) {
            $this->error("No se encontraron usuarios");
            return 1;
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
            label: 'Escribe el rol que quieres enlazar',
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

        if (confirm("Seguro que deseas asignar el rol <<{$selectedRol['name']}>> al usuario: {$userSelect['name']}?")) {
            $response = AppBoundRequest::assignRoleToUser(
                uriUser: $selectedUserId,
                uriApplicationRole: $uriRol
            );
            if ($response->getStatusCode() !== 200) {
                $this->error("Error al asignar el rol: " . $response->getContent());
                return 1;
            }
            $this->info("¡Listo! El rol '{$selectedRol['name']}' ha sido asignado al usuario seleccionado.");
        } else {
            $this->info('Operación cancelada.');
        }

        return 0;
    }
}
