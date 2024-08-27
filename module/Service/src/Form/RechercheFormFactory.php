<?php

namespace Service\Form;

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
        $fieldset = new RechercheForm();
        if ($container->has('BjyAuthorize\Service\Authorize')) {
            $fieldset->setServiceAuthorize($container->get('BjyAuthorize\Service\Authorize'));
        }

        return $fieldset;
    }
}