<?php

namespace Application\Service\Traits;

use Application\Service\NotificationIndicateur;

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
     *
     * @return self
     */
    public function setServiceNotificationIndicateur(NotificationIndicateur $serviceNotificationIndicateur)
    {
        $this->serviceNotificationIndicateur = $serviceNotificationIndicateur;

        return $this;
    }



    /**
     * @return NotificationIndicateur
     */
    public function getServiceNotificationIndicateur()
    {
        if (empty($this->serviceNotificationIndicateur)) {
            $this->serviceNotificationIndicateur = \Application::$container->get('NotificationIndicateurService');
        }

        return $this->serviceNotificationIndicateur;
    }
}