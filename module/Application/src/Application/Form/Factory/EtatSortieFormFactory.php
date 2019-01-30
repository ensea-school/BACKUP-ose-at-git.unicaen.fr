<?php

namespace Application\Form\Factory;

use Zend\Form\FormElementManager as ContainerInterface;
use Application\Form\EtatSortieForm;



/**
 * Description of EtatSortieFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EtatSortieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtatSortieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        /* On quitte le FormElementManager */
        $container = $container->getServiceLocator();

        $form = new EtatSortieForm;

        return $form;
    }
}