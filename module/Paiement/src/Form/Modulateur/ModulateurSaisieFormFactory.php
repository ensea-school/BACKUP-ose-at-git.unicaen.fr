<?php

namespace Paiement\Form\Modulateur;

use Psr\Container\ContainerInterface;



/**
 * Description of ModulateurSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ModulateurSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ModulateurSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ModulateurSaisieForm
    {
        $form = new ModulateurSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}