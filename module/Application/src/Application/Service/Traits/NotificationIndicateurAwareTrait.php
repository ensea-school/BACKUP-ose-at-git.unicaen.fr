<?php

namespace Application\Service\Traits;

use Application\Service\NotificationIndicateur;
use Common\Exception\RuntimeException;

trait NotificationIndicateurAwareTrait
{
    /**
     * description
     *
     * @var NotificationIndicateur
     */
    private $serviceNotificationIndicateur;

    /**
     *
     * @param NotificationIndicateur $serviceNotificationIndicateur
     * @return self
     */
    public function setServiceNotificationIndicateur( NotificationIndicateur $serviceNotificationIndicateur )
    {
        $this->serviceNotificationIndicateur = $serviceNotificationIndicateur;
        return $this;
    }

    /**
     *
     * @return NotificationIndicateur
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceNotificationIndicateur()
    {
        if (empty($this->serviceNotificationIndicateur)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationNotificationIndicateur');
        }else{
            return $this->serviceNotificationIndicateur;
        }
    }

}