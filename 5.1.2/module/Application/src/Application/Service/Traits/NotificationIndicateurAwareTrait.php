<?php

namespace Application\Service\Traits;

use Application\Service\NotificationIndicateur;
use Application\Module;
use RuntimeException;

/**
 * Description of NotificationIndicateurAwareTrait
 *
 * @author UnicaenCode
 */
trait NotificationIndicateurAwareTrait
{
    /**
     * @var NotificationIndicateur
     */
    private $serviceNotificationIndicateur;





    /**
     * @param NotificationIndicateur $serviceNotificationIndicateur
     * @return self
     */
    public function setServiceNotificationIndicateur( NotificationIndicateur $serviceNotificationIndicateur )
    {
        $this->serviceNotificationIndicateur = $serviceNotificationIndicateur;
        return $this;
    }



    /**
     * @return NotificationIndicateur
     * @throws RuntimeException
     */
    public function getServiceNotificationIndicateur()
    {
        if (empty($this->serviceNotificationIndicateur)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceNotificationIndicateur = $serviceLocator->get('NotificationIndicateurService');
        }
        return $this->serviceNotificationIndicateur;
    }
}