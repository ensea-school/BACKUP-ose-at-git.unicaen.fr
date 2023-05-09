<?php

namespace Mission\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of MissionFormFactory
 *
 * @author Antony Le Courtes  <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return OffreEmploiForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): OffreEmploiForm
    {
        $form = new OffreEmploiForm();

        /* Injectez vos dépendances ICI */

        return $form;
    }
}