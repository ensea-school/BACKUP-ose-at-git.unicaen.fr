<?php

namespace Application\Entity\Db\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\Entity\Db\Dossier;

/**
 * Description of DossierListener
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierListener
{
    /**
     * @var bool
     */
    static public $created;
    
    /**
     * @var bool
     */
    static public $modified;
    
    /**
     * 
     * @param \Application\Entity\Db\Dossier $dossier
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function preUpdate(Dossier $dossier, PreUpdateEventArgs $event)
    {
        self::$modified = (bool) $event->getEntityChangeSet();
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Dossier $dossier
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function prePersist(Dossier $dossier, LifecycleEventArgs $event)
    {
        self::$created = true;
    }
}