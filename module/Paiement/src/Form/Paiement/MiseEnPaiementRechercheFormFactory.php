<?php

namespace Paiement\Form\Paiement;

use Psr\Container\ContainerInterface;



/**
 * Description of MiseEnPaiementRechercheFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementRechercheFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MiseEnPaiementRechercheForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MiseEnPaiementRechercheForm
    {
        $form = new MiseEnPaiementRechercheForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}