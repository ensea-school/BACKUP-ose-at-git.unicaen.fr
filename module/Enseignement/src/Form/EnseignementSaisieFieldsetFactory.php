<?php

namespace Enseignement\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of SaisieFieldsetFactory
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
     * @return SaisieFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $fieldset = new EnseignementSaisieFieldset();
        $fieldset->setServiceAuthorize($container->get('BjyAuthorize\Service\Authorize'));

        return $fieldset;
    }
}