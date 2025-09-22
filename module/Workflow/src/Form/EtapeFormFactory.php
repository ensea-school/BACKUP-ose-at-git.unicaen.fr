<?php

namespace Workflow\Form;

use Psr\Container\ContainerInterface;



/**
 * Description of EtapeFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeForm
    {
        $form = new EtapeForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}