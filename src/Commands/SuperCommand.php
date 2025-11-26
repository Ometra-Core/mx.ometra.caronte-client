<?php

namespace Ometra\Caronte\Commands;

use Illuminate\Console\Command;
use Ometra\Caronte\AppBound;

abstract class SuperCommand extends Command
{
    final public function handle()
    {
        AppBound::initializeSettings();
        return $this->executeCommand();
    }

    abstract protected function executeCommand();

    public function __construct()
    {
        parent::__construct();
    }
}
