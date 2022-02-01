<?php

namespace Indicateur\Service;


/**
 * Description of NotificationIndicateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait NotificationIndicateurServiceAwareTrait
{
    protected ?NotificationIndicateurService $serviceNotificationIndicateur;



    /**
     * @param NotificationIndicateurService|null $serviceNotificationIndicateur
     *
     * @return self
     */
    public function setServiceNotificationIndicateur( ?NotificationIndicateurService $serviceNotificationIndicateur )
    {
        $this->serviceNotificationIndicateur = $serviceNotificationIndicateur;

        return $this;
    }



    public function getServiceNotificationIndicateur(): ?NotificationIndicateurService
    {
        if (!$this->serviceNotificationIndicateur){
            $this->serviceNotificationIndicateur = \Application::$container->get(NotificationIndicateurService::class);
        }

        return $this->serviceNotificationIndicateur;
    }
}