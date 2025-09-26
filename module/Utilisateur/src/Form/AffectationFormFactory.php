<?php

namespace Utilisateur\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of AffectationFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class AffectationFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new AffectationForm();

        return $form;
    }
}