<?php

namespace Formule\Command;

use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;


/**
 * Description of CalculCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CalculCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CalculCommand
    {
        $command = new CalculCommand;

        $command->setServiceTableauBord($container->get(TableauBordService::class));

        return $command;
    }
}