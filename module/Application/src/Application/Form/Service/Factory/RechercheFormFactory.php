<?php

namespace Application\Form\Service\Factory;

use Application\Form\Service\SaisieFieldset;
use Interop\Container\ContainerInterface;


/**
 * Description of SaisieFieldsetFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class rechercheFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SaisieFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $fieldset = new SaisieFieldset;
        $fieldset->setServiceAuthorize($container->get('BjyAuthorize\Service\Authorize'));

        return $fieldset;
    }
}