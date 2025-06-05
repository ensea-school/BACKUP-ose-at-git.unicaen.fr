<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Workflow\Controller;

use Application\Controller\PeriodeController;
use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;

class WorkflowControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return PeriodeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new WorkflowController();

        $controller->setServiceTableauBord($container->get(TableauBordService::class));

        return $controller;
    }
}