<?php

namespace OSETest;

use Doctrine\ORM\EntityManager;

/**
 * Code commun aux classes utilisant le gestionnaire d'entité Doctrine.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait EntityManagerAwareTrait
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @param string $name
     * @return EntityManager
     */
    protected function getEntityManager($name = 'orm_default')
    {
        if (null === $this->em) {
            $this->em = Bootstrap::getServiceManager()->get(\Application\Constants::BDD);
        }

        return $this->em;
    }
}