<?php

namespace Indicateur\Service;


/**
 * Description of NotificationIndicateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait NotificationIndicateurServiceAwareTrait
{
    protected ?NotificationIndicateurService $serviceNotificationIndicateur = null;



    /**
     * @param NotificationIndicateurService $serviceNotificationIndicateur
     *
     * @return self
     */
    public function setServiceNotificationIndicateur(?NotificationIndicateurService $serviceNotificationIndicateur)
    {
        $this->serviceNotificationIndicateur = $serviceNotificationIndicateur;

        return $this;
    }



    public function getServiceNotificationIndicateur(): ?NotificationIndicateurService
    {
        if (empty($this->serviceNotificationIndicateur)) {
            $this->serviceNotificationIndicateur = \Unicaen\Framework\Application\Application::getInstance()->container()->get(NotificationIndicateurService::class);
        }

        return $this->serviceNotificationIndicateur;
    }
}