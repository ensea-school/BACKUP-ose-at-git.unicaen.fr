<?php

namespace Application\Form\Plafond\Factory;

use Application\Service\PlafondEtatService;
use Application\Service\PlafondService;
use Zend\Form\FormElementManager as ContainerInterface;
use Application\Form\Plafond\PlafondApplicationForm;



/**
 * Description of PlafondApplicationFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondApplicationFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondApplicationForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $container = $container->getServiceLocator();

        $form = new PlafondApplicationForm;
        $form->setServiceStructure($container->get('ApplicationStructure'));
        $form->setServiceAnnee($container->get('ApplicationAnnee'));
        $form->setServicePlafond($container->get(PlafondService::class));
        $form->setServicePlafondEtat($container->get(PlafondEtatService::class));

        return $form;
    }
}