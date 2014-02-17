<?php
namespace Common\ORM\Event\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;

/**
 * Listener Doctrine permettant l'ajout automatique de l'heure de création/modification 
 * et de l'auteur de création/modification de toute entité avant qu'elle soit persistée.
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Histo implements EventSubscriber
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $sl;
    
    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sl
     */
    public function __construct(\Zend\ServiceManager\ServiceLocatorInterface $sl)
    {
        $this->sl = $sl;
    }
    
    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity(); /* @var $entity \Application\Entity\Db\HistoInterface */
        
        // l'entité doit implémenter l'interface requise
        if (!$entity instanceof \Application\Entity\Db\HistoInterface) {
            return;
        }
        
        // horodatage
        if (null === $entity->getHistoDebut()) {
            $entity->setHistoDebut(new \DateTime());
        }
        $entity->setHistoModification(new \DateTime());
        
        // inscription de l'utilisateur connecté comme auteur de la création/modification
        $authenticationService = $this->sl->get('Zend\Authentication\AuthenticationService');
        if ($authenticationService->hasIdentity()) {
            $identity = $authenticationService->getIdentity();
            if (!isset($identity['db'])) {
//                throw new \Common\Exception\LogicException("Aucune donnée d'identité 'db' disponible.");
                return;
            }
            $user = $identity['db']; /* @var $user \Application\Entity\Db\User */
            if (!$user instanceof \Application\Entity\Db\User) {
//                throw new \Common\Exception\LogicException("Type des données d'identité 'db' inattendu.");
                return;
            }

            if (null === $entity->getHistoCreateur()) {
                $entity->setHistoCreateur($user);
            }
            $entity->setHistoModificateur($user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::prePersist);
    }
}