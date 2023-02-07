<?php

namespace Paiement\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of TauxFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TauxValeurFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxValeurForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxValeurForm
    {
        $form = new TauxValeurForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}