<?php

namespace Application\Form\Intervenant\Factory;

use Application\Form\Intervenant\IntervenantDossierForm;
use Psr\Container\ContainerInterface;


/**
 * Description of ModeleFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantDossierFormFactory
{

    protected $options;

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form      = new IntervenantDossierForm( $options['intervenant']);
        return $form;
    }


}