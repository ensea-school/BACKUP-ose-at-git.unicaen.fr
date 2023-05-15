<?php

namespace Paiement\Form\CentreCout;

use Psr\Container\ContainerInterface;



/**
 * Description of CentreCoutStructureSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CentreCoutStructureSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CentreCoutStructureSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CentreCoutStructureSaisieForm
    {
        $form = new CentreCoutStructureSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}