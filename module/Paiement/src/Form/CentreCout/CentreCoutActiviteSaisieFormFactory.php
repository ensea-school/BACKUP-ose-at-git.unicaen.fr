<?php

namespace Paiement\Form\CentreCout;

use Psr\Container\ContainerInterface;



/**
 * Description of CentreCoutActiviteSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CentreCoutActiviteSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CentreCoutActiviteSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CentreCoutActiviteSaisieForm
    {
        $form = new CentreCoutActiviteSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}