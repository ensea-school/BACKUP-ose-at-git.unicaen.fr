<?php

namespace Application\Form\Etablissement;

use Psr\Container\ContainerInterface;

/**
 * Description of EtablissementSaisieFormFactory
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
     * @return EtablissementSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new EtablissementSaisieForm();

        return $form;
    }
}