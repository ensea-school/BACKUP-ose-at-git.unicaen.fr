<?php

namespace OffreFormation\Form\TauxMixite;

use Psr\Container\ContainerInterface;


/**
 * Description of TauxMixiteFormFactoryFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TauxMixiteFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxMixiteForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxMixiteFormFactory
    {
        $form = new TauxMixiteForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

