<?php

namespace Application\Service\Traits;

use Application\Service\NotificationIndicateurService;

/**
 * Description of NotificationIndicateurAwareTrait
 *
 * @author UnicaenCode
 */
trait NotificationIndicateurServiceAwareTrait
{
    /**
     * @var NotificationIndicateurService
     */
    private $serviceNotificationIndicateur;



    /**
     * @param NotificationIndicateurService $serviceNotificationIndicateur
     *
     * @return self
     */
    public function setServiceNotificationIndicateur(NotificationIndicateurService $serviceNotificationIndicateur)
    {
        $this->serviceNotificationIndicateur = $serviceNotificationIndicateur;

        return $this;
    }



    /**
     * @return NotificationIndicateurService
     */
    public function getServiceNotificationIndicateur()
    {
        if (empty($this->serviceNotificationIndicateur)) {
            $this->serviceNotificationIndicateur = \Application::$container->get(NotificationIndicateurService::class);
        }

        return $this->serviceNotificationIndicateur;
    }
}