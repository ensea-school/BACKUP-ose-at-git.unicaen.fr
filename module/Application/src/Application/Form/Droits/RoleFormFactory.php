<?php

namespace Application\Form\Droits;

use Interop\Container\ContainerInterface;



/**
 * Description of RoleFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class RoleFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new RoleForm;

        return $form;
    }
}