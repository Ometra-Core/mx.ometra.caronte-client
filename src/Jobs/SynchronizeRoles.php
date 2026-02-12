<?php

namespace Ometra\Caronte\Jobs;

use Ometra\Caronte\CaronteRoleManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Exception;
use Equidna\Toolkit\Exceptions\NotFoundException;

class SynchronizeRoles implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            CaronteRoleManager::getRoles();
        } catch (Exception $e) {
            throw new NotFoundException(message: 'job failed: ' . $e->getMessage());
        }
    }
}
