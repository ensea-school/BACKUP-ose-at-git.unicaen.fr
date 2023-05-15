<?php

namespace Paiement\Form\Budget;

use Psr\Container\ContainerInterface;



/**
 * Description of DotationSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DotationSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DotationSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DotationSaisieForm
    {
        $form = new DotationSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}