<?php

/**
 * Base command class for all Caronte CLI commands.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Console\Commands
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/Ometra-Core/mx.ometra.caronte-client Documentation
 */

namespace Ometra\Caronte\Console\Commands;

use Illuminate\Console\Command;
use Ometra\Caronte\CaronteRoleManager;

abstract class CaronteCommand extends Command
{
    final public function handle()
    {
        return $this->executeCommand();
    }

    abstract protected function executeCommand();

    public function __construct()
    {
        parent::__construct();
    }
}
