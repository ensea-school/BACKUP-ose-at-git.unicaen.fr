<?php

namespace Application\Form\Contrat\Factory;

use Application\Service\StatutIntervenantService;
use Application\Service\StructureService;
use Interop\Container\ContainerInterface;
use Application\Form\Contrat\ModeleForm;



/**
 * Description of ModeleFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ModeleFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ModeleForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new ModeleForm;
        $form->setServiceStatutIntervenant($container->get(StatutIntervenantService::class));
        $form->setServiceStructure($container->get(StructureService::class));

        return $form;
    }
}