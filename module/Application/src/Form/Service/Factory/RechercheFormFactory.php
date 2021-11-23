<?php

namespace Application\Form\Service\Factory;

use Application\Form\Service\RechercheForm;
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
        $fieldset->setServiceAuthorize($container->get('BjyAuthorize\Service\Authorize'));

        return $fieldset;
    }
}