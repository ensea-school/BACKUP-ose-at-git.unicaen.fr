<?php

namespace Dossier\Form\Factory;

use Dossier\Form\IntervenantDossierForm;
use Psr\Container\ContainerInterface;


/**
 * Description of ModeleFormFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class IntervenantDossierFormFactory
{

    protected $options;



    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new IntervenantDossierForm;

        return $form;
    }

}