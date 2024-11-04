<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;


/**
 * Description of CalculTableauxBordCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculTableauxBordCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CalculTableauxBordCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CalculTableauxBordCommand
    {
        $command = new CalculTableauxBordCommand;

        $command->setServiceTableauBord($container->get(TableauBordService::class));

        return $command;
    }
}