<?php

namespace Paiement\Form\TypeRessource;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeRessourceSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeRessourceSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeRessourceSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeRessourceSaisieForm
    {
        $form = new TypeRessourceSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}