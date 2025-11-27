<?php

namespace Ometra\Caronte\Commands\CrudRoles;

use Ometra\Caronte\AppBound;
use Ometra\Caronte\Commands\SuperCommand;

use function Laravel\Prompts\text;
use function Laravel\Prompts\table;

class ShowRoles extends SuperCommand
{
    protected $signature = 'caronte-client:show-roles';
    protected $description = 'Show Roles within the application';

    public function executeCommand()
    {
        $response = AppBound::showRoles();
        $roles = json_decode($response, true);
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
