<?php

namespace Application\Form\Plafond\Factory;

use Interop\Container\ContainerInterface;
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
        $form = new PlafondApplicationForm;

        return $form;
    }
}