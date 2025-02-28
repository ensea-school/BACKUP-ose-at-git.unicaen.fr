<?php

namespace Application\ORM\Event\Listeners;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use UnicaenApp\Service\EntityManagerAwareInterface;

/**
 * Description of HistoriqueListener
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EntityManagerListener implements EventSubscriber
{

    public function postload(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof EntityManagerAwareInterface) {
            if (!$entity->getEntityManager()) {
                $entity->setEntityManager($args->getEntityManager());
            }
        }
    }



    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::postLoad];
    }

}