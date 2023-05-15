<?php

namespace Paiement\Form\Modulateur;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeModulateurSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeModulateurSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeModulateurSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeModulateurSaisieForm
    {
        $form = new TypeModulateurSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}