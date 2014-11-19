<?php
namespace Common\ORM\Event\Listeners;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;

/**
 * Listener Doctrine permettant la mise à jour automatique des résultats de formule lorsqu'une donnée concernée est modifiée
 * 
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleListener implements EventSubscriber, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * 
     * @param PostFlushEventArgs $args
     * @return type
     */
    protected function updateFormule(PostFlushEventArgs $args)
    {
        $sql = "BEGIN OSE_FORMULE.MAJ_ALL_IDT; END;";
        $args->getEntityManager()->getConnection()->executeQuery($sql);
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $this->updateFormule($args);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::postFlush);
    }   
}