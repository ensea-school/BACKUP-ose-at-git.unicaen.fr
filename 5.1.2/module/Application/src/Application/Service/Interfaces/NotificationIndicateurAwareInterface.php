<?php

namespace Application\Service\Interfaces;

use Application\Service\NotificationIndicateur;
use RuntimeException;

/**
 * Description of NotificationIndicateurAwareInterface
 *
 * @author UnicaenCode
 */
interface NotificationIndicateurAwareInterface
{
    /**
     * @param NotificationIndicateur $serviceNotificationIndicateur
     * @return self
     */
    public function setServiceNotificationIndicateur( NotificationIndicateur $serviceNotificationIndicateur );



    /**
     * @return NotificationIndicateurAwareInterface
     * @throws RuntimeException
     */
    public function getServiceNotificationIndicateur();
}