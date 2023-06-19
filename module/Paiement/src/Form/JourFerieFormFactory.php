<?php

namespace Paiement\Form;

use Psr\Container\ContainerInterface;



/**
 * Description of JourFerieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class JourFerieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return JourFerieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): JourFerieForm
    {
        $form = new JourFerieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}