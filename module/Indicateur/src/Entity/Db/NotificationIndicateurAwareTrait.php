<?php

namespace Indicateur\Entity\Db;

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
    private $notificationIndicateur;



    /**
     * @param NotificationIndicateur $notificationIndicateur
     *
     * @return self
     */
    public function setNotificationIndicateur(NotificationIndicateur $notificationIndicateur = null)
    {
        $this->notificationIndicateur = $notificationIndicateur;

        return $this;
    }



    /**
     * @return NotificationIndicateur
     */
    public function getNotificationIndicateur()
    {
        return $this->notificationIndicateur;
    }
}