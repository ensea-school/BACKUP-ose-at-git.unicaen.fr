<?php

namespace Application\Notification;

use Application\Entity\Db\Notification as NotificationEntity;

/**
 * Description of AbstractNotification
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractNotification implements NotificationInterface
{
    protected $entity;
    
    /**
     * 
     * @param NotificationEntity $entity
     */
    public function __construct(NotificationEntity $entity)
    {
        $this->entity = $entity;
    }
    
    /**
     * 
     * @return NotificationEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
}