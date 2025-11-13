<?php

namespace Equidna\Caronte\Commands;

use Illuminate\Console\Command;
use Equidna\Caronte\AppBoundRequest;
use Equidna\Caronte\AppBound;

abstract class SuperCommand extends Command
{
    final public function handle()
    {
        $this->initializeSettings();

        // 2. Llamar al método abstracto que el hijo implementará
        return $this->executeCommand();
    }

    abstract protected function executeCommand();

    public function __construct()
    {
        parent::__construct();
    }

    public function initializeSettings()
    {
        AppBound::synchronizeRoles();
        $response = AppBoundRequest::showRoles();
        $response = $response->getData(true);
        $roles = $response['data'] ?? [];
        $roles = json_decode($roles, true);
        if (empty($roles)) {
            AppBound::saveSetting('roles', []);
        } else {
            $mappedRoles = [];
            foreach ($roles as $role) {
                $mappedRoles[$role['uri_applicationRole']] = $role;
            }
            AppBound::saveSetting('roles', $mappedRoles);
        }
    }
}
