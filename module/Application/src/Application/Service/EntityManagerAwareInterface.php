<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Interface commune aux classes utilisant le gestionnaire d'entité Doctrine.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface EntityManagerAwareInterface
{
    /**
     * 
     * @param EntityManager $entityManager
     * @return self
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * Retourne le gestionnaire d'entité.
     *
     * @return EntityManager
     */
    public function getEntityManager();
}