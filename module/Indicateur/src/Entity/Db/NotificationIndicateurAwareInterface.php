<?php

namespace Indicateur\Entity\Db;

/**
 * Description of NotificationIndicateurAwareInterface
 *
 * @author UnicaenCode
 */
interface NotificationIndicateurAwareInterface
{
    /**
     * @param NotificationIndicateur $notificationIndicateur
     *
     * @return self
     */
    public function setNotificationIndicateur(NotificationIndicateur $notificationIndicateur = null);



    /**
     * @return NotificationIndicateur
     */
    public function getNotificationIndicateur();
}