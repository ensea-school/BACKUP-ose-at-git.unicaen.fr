<?php

namespace Paiement\Form\Paiement;

use Psr\Container\ContainerInterface;



/**
 * Description of MiseEnPaiementFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MiseEnPaiementForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MiseEnPaiementForm
    {
        $form = new MiseEnPaiementForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}