<?php

namespace Enseignement\Form;

use Unicaen\Framework\Authorize\Authorize;
use Psr\Container\ContainerInterface;


/**
 * Description of EnseignementSaisieFieldsetFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EnseignementSaisieFieldsetFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EnseignementSaisieFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $fieldset = new EnseignementSaisieFieldset(
            $container->get(Authorize::class),
        );

        return $fieldset;
    }
}