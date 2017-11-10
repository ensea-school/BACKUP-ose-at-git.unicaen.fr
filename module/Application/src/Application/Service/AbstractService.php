<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Service abstrait
 *
 * Permet d'accéder facilement aux paramètres globaux de l'application
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AbstractService implements ServiceLocatorAwareInterface, EntityManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use EntityManagerAwareTrait;
    use Traits\ContextServiceAwareTrait;

    /**
     *
     * @return \BjyAuthorize\Service\Authorize
     */
    public function getAuthorize()
    {
        return $this->getServiceLocator()->get('BjyAuthorize\Service\Authorize');
    }

}