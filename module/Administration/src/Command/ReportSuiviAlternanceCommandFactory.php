<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;

class ReportSuiviAlternanceCommandFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ReportSuiviAlternanceCommand
    {
        $command = new ReportSuiviAlternanceCommand;
        $command->setBdd($container->get(Bdd::class));

        return $command;
    }
}
