<?php
namespace Application\ORM\Event\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;

/**
 * Listener Doctrine.
 * 
 * Ajout automatique de l'heure de création/modification et de l'auteur de création/modification
 * de toute entité avant qu'elle soit persistée.
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Histo implements EventSubscriber
{
    /**
     * @var mixed
     */
    protected $identity;
    
    /**
     * 
     * @param mixed $identity
     */
    public function __construct($identity = null)
    {
        $this->identity = $identity;
    }
    
    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity(); /* @var $entity \Application\Entity\Db\HistoInterface */
        
        if (!$entity instanceof \Application\Entity\Db\HistoInterface) {
            return;
        }
        
        // horodatage
        if (null === $entity->getHistoDebut()) {
            $entity->setHistoDebut(new \DateTime());
        }
        $entity->setHistoModification(new \DateTime());
        
//        if ($this->identity) {
//            // on extrait l'identifiant de l'utilisateur dans les données d'identité
//            if (null === $entity->getHistoCreateur()) {
//                $entity->setHistoCreateur($this->identity->getSupannAliasLogin());
//            }
//            $entity->setHistoModificateur($this->identity->getSupannAliasLogin());
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::prePersist);
    }
}