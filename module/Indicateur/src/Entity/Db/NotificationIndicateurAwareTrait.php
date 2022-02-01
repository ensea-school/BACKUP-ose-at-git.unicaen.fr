<?php

namespace Indicateur\Entity\Db;


/**
 * Description of NotificationIndicateurAwareTrait
 *
 * @author UnicaenCode
 */
trait NotificationIndicateurAwareTrait
{
    protected ?NotificationIndicateur $notificationIndicateur;



    /**
     * @param NotificationIndicateur|null $notificationIndicateur
     *
     * @return self
     */
    public function setNotificationIndicateur( ?NotificationIndicateur $notificationIndicateur )
    {
        $this->notificationIndicateur = $notificationIndicateur;

        return $this;
    }



    public function getNotificationIndicateur(): ?NotificationIndicateur
    {
        return $this->notificationIndicateur;
    }
}