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
    use Traits\ContextAwareTrait;



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
     * 
     * @return \BjyAuthorize\Service\Authorize
     */
    public function getAuthorize()
    {
        return $this->getServiceLocator()->get('BjyAuthorize\Service\Authorize');
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
        if ($runEx) {
            /**
             * @todo: pb avec UnAuthorizedException : le code 403 est interprêté en AJAX comme une fin de session 
             * (cf. unicaen.js)
             */
//            throw new UnAuthorizedException($why);
            throw new \Common\Exception\MessageException($why);
        }
        return false;
    }
}