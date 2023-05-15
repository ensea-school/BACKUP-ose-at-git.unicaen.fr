<?php

namespace Paiement\Form\MotifNonPaiement;

use Psr\Container\ContainerInterface;



/**
 * Description of MotifNonPaiementSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MotifNonPaiementSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MotifNonPaiementSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MotifNonPaiementSaisieForm
    {
        $form = new MotifNonPaiementSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}