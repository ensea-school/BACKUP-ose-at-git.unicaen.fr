<?php
namespace Common\ORM\Event\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Common\Exception\RuntimeException;
use Application\Entity\Db\ValiditeAwareInterface;

/**
 * Listener Doctrine permettant l'initialisation de la date de début de validité.
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValiditeListener implements EventSubscriber
{
    /**
     * 
     * @param LifecycleEventArgs $args
     * @return self
     * @throws RuntimeException
     */
    protected function updateValidite(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        // l'entité doit implémenter l'interface requise
        if (!$entity instanceof ValiditeAwareInterface) {
            return;
        }
        /* @var $entity \Application\Entity\Db\ValiditeAwareInterface */
        
        $now = new \DateTime();
        
        if (null === $entity->getValiditeDebut()) {
            $entity->setValiditeDebut($now);
        }
        
        return $this;
    }
    
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateValidite($args);
    }
    
    /**
     * @param PreUpdateEventArgs $args
     */
//    public function preUpdate(PreUpdateEventArgs $args)
//    {
//        $this->updateValidite($args);
//    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::prePersist/*, Events::preUpdate*/);
    }   
}