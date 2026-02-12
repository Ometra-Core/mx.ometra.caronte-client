<?php

/**
 * Deprecated base command class; use CaronteCommand instead.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Console\Commands
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/Ometra-Core/mx.ometra.caronte-client Documentation
 * @deprecated 1.3.4 Use CaronteCommand
 */

namespace Ometra\Caronte\Console\Commands;

use Illuminate\Console\Command;
use Ometra\Caronte\CaronteRoleManager;

/**
 * @deprecated Use CaronteCommand instead.
 */
abstract class SuperCommand extends Command
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
