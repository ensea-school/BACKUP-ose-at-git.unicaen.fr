<?php

namespace Application\Form\Departement;

use Psr\Container\ContainerInterface;

/**
 * Description of DepartementSaisieFormFactory
 *
 * @author Joriot Florian
 */
class DepartementSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DepartementSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new DepartementSaisieForm();

        return $form;
    }
}