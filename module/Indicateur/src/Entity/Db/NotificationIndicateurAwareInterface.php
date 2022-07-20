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
     * @param NotificationIndicateur|null $notificationIndicateur
     *
     * @return self
     */
    public function setNotificationIndicateur( ?NotificationIndicateur $notificationIndicateur );



    public function getNotificationIndicateur(): ?NotificationIndicateur;
}