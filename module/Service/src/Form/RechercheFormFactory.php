<?php

namespace Service\Form;

use Unicaen\Framework\Authorize\Authorize;
use Psr\Container\ContainerInterface;


/**
 * Description of SaisieFieldsetFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class RechercheFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RechercheForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $fieldset = new RechercheForm(
            $container->get(Authorize::class),
        );

        return $fieldset;
    }
}