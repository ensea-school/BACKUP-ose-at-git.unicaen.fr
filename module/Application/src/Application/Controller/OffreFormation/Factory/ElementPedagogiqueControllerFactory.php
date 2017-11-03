<?php

namespace Application\Controller\OffreFormation\Factory;

use Application\Form\OffreFormation\VolumeHoraireEnsForm;
use Zend\Mvc\Controller\ControllerManager as ContainerInterface;
use Application\Controller\OffreFormation\ElementPedagogiqueController;



/**
 * Description of ElementPedagogiqueControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementPedagogiqueController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $container = $container->getServiceLocator();

        $controller = new ElementPedagogiqueController;
        $controller->setServiceLocator($container);
        $controller->setFormOffreFormationVolumeHoraireEns($container->get('FormElementManager')->get(VolumeHoraireEnsForm::class));

        return $controller;
    }
}