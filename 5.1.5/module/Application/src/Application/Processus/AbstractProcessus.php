<?php

namespace Application\Processus;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Processus abstrait
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AbstractProcessus implements ServiceLocatorAwareInterface, EntityManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use EntityManagerAwareTrait;


    /**
     *
     * @return \BjyAuthorize\Service\Authorize
     */
    public function getAuthorize()
    {
        return $this->getServiceLocator()->get('BjyAuthorize\Service\Authorize');
    }

}