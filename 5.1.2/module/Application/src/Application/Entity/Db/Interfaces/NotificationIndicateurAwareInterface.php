<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\NotificationIndicateur;

/**
 * Description of NotificationIndicateurAwareInterface
 *
 * @author UnicaenCode
 */
interface NotificationIndicateurAwareInterface
{
    /**
     * @param NotificationIndicateur $notificationIndicateur
     * @return self
     */
    public function setNotificationIndicateur( NotificationIndicateur $notificationIndicateur = null );



    /**
     * @return NotificationIndicateur
     */
    public function getNotificationIndicateur();
}