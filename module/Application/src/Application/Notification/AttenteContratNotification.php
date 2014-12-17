<?php

namespace Application\Notification;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteContratNotification extends AbstractNotification
{
    public function getIntitule()
    {
        return $this->getEntity()->getLibelle();
    }
    
    public function getDetails()
    {
        
    }
    
    
}