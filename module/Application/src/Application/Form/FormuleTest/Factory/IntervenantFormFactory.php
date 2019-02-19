<?php

namespace Application\Form\FormuleTest\Factory;

use Zend\Form\FormElementManager as ContainerInterface;
use Application\Form\FormuleTest\IntervenantForm;



/**
 * Description of IntervenantFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return IntervenantForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        /* On quitte le FormElementManager */
        $container = $container->getServiceLocator();

        $form = new IntervenantForm;
        /* Injectez vos dépendances ICI */

        return $form;
    }
}