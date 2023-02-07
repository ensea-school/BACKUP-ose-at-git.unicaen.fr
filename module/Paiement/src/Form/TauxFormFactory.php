<?php

namespace Paiement\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of TauxFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TauxFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxForm
    {
        $form = new TauxForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}