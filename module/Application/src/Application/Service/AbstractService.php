<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;

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

    /**
     * Retourne le gestionnaire d'entités Doctrine
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (empty($this->entityManager)) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    /**
     * Si on ne peut pas faire quelque chose, alors soit on lève une exception soit on renvoie false
     *
     * @param string $why               Explication de l'interdiction
     * @param boolean $runEx            Détermine si les exceptions doivent être lancées ou s'il suffit de retourner FALSE
     * @return boolean                  Résultat
     * @throws UnAuthorizedException    Exception lancée (si c'est voulu)
     */
    public function cannotDoThat($why, $runEx=false)
    {
        if ($runEx){
            throw new UnAuthorizedException($why);
        }
        return false;
    }
}