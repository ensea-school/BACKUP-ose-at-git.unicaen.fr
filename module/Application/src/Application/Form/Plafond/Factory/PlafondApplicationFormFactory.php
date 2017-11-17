<?php

namespace Application\Form\Plafond\Factory;

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

        return $form;
    }
}