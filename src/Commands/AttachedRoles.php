<?php

namespace Equidna\Caronte\Commands;

use Equidna\Caronte\Commands\SuperCommand;
use Equidna\Caronte\AppBoundRequest;

class AttachedRoles extends SuperCommand
{
    protected $signature = 'caronte-client:attached-roles';
    protected $description = 'Associates a specific role with a user';

    public function executeCommand()
    {
        $searchUSer = $this->ask('¿Qué usuario estás buscando? (Escribe el nombre o email)');

        if (empty($searchUSer)) {
            $this->info('No se ingresó ningún término.');
            return 1;
        }

        $response = AppBoundRequest::showUsers(paramSearch: $searchUSer);
        $response = $response->getData(true);
        $users = $response['data'] ?? [];
        $users = json_decode($users, true);

        if (empty($users)) {
            $this->error("No se encontraron usuarios con el término: '{$searchUSer}'");
            return 1;
        }

        $options = [];
        $lookupMap = [];

        foreach ($users as $user) {
            $key = "{$user['name']} - (uri_user: {$user['uri_user']})";
            $options[] = $key;
            $lookupMap[$key] = $user['uri_user'];
        }

        $selectedOption = $this->choice(
            'Se encontraron los siguientes usuarios. ¿Cuál quieres seleccionar?',
            $options
        );

        // Usamos el mapa para obtener el ID real
        $selectedUserId = $lookupMap[$selectedOption];

        $response = AppBoundRequest::showRoles();
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

        $selectedLabel = $this->choice("Selecciona el rol que quieres enlazar:", array_keys($choices));
        $selectedUri = $choices[$selectedLabel];
        $selectedRol = collect($roles)->firstWhere('uri_applicationRole', $selectedUri);
        $uriRol = $selectedRol['uri_applicationRole'] ?? null;

        if (!$selectedRol) {
            $this->error("Acción no encontrada.");
            return 1;
        }

        $this->info("Has seleccionado: {$selectedRol['name']}");

        if ($this->confirm("Seguro que deseas asignar el rol: {$selectedRol['name']} al usuario: {$selectedUserId}?")) {
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
