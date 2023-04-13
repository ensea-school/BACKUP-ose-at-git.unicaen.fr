<?php

namespace Mission\Form;

use Psr\Container\ContainerInterface;



/**
 * Description of MissionSuiviFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionSuiviFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionSuiviForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionSuiviForm
    {
        $form = new MissionSuiviForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}