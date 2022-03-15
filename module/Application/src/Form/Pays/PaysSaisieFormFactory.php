<?php

namespace Application\Form\Pays;

use Psr\Container\ContainerInterface;

/**
 * Description of PaysSaisieFormFactory
 *
 * @author Joriot Florian
 */
class EtablissementSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PaysSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new PaysSaisieForm();

        return $form;
    }
}