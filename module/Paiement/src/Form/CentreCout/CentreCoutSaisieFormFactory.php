<?php

namespace Paiement\Form\CentreCout;

use Psr\Container\ContainerInterface;



/**
 * Description of CentreCoutSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CentreCoutSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CentreCoutSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CentreCoutSaisieForm
    {
        $form = new CentreCoutSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}