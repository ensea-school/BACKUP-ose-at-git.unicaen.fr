<?php

namespace Application\Form\Service\Factory;

use Psr\Container\ContainerInterface;
use Application\Form\Service\SaisieFieldset;


/**
 * Description of SaisieFieldsetFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldsetFactory
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