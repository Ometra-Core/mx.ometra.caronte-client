<?php

namespace Ometra\Caronte\Commands\CrudUsers;

use Ometra\Caronte\AppBoundRequest;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;

class ShowRolesByUser extends Command
{
    protected $signature = 'caronte-client:users-roles {uri_user}';
    protected $description = 'Show Roles attached by user within the application';

    public function handle()
    {
        $uri_user = $this->argument('uri_user');
        $response = AppBoundRequest::showRolesUser(uri_user: $uri_user);
        $roles = json_decode($response['data'], true);
        if (empty($roles)) {
            $this->warn("No hay roles registrados.");
            return 0;
        }
        $rows = collect($roles)->map(function ($role) {
            return [
                $role['name'],
                $role['description'],
            ];
        })->all();
        table(
            headers: ['Nombre', 'Descripción'],
            rows: $rows
        );

        return 0;
    }
}
