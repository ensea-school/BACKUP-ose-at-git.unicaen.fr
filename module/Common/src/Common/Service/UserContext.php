<?php

namespace Common\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Entity\UserInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class UserContext implements ServiceLocatorAwareInterface
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $sl;

    /**
     * @var mixed
     */
    protected $identity;





    /**
     *
     * @return mixed
     */
    protected function getIdentity()
    {
        if (null === $this->identity) {
            $authenticationService = $this->sl->get('Zend\Authentication\AuthenticationService');
            if ($authenticationService->hasIdentity()) {
                $this->identity = $authenticationService->getIdentity();
            }
        }

        return $this->identity;
    }

    /**
     * Retourne l'utilisateur courant
     *
     * @return UserInterface
     */
    public function getCurrentUser()
    {
        if (($identity = $this->getIdentity())) {
            if (isset($identity['db']) && $identity['db'] instanceof UserInterface) {
                return $identity['db'];
            }
        }
        return null;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return self
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->sl;
    }
}