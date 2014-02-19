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
     * @var mixed
     */
    protected $identity;
    
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
        $em     = $args->getEntityManager();
        $entity = $args->getEntity();
        $user   = null;
        
        // l'entité doit implémenter l'interface requise
        if (!$entity instanceof \Application\Entity\Db\HistoriqueAwareInterface) {
            return;
        }
        
        // l'utilisateur connecté sera l'auteur de la création/modification
        if (($identity = $this->getIdentity())) {
            if (isset($identity['db']) && $identity['db'] instanceof \Application\Entity\Db\Utilisateur) {
                $user = $identity['db']; /* @var $user \Application\Entity\Db\Utilisateur */
            }
        }
        
//        var_dump(get_class($entity), is_null($user), is_null($entity->getHistorique()));
        
        if (!($histo = $entity->getHistorique())) {
            $histo = new \Application\Entity\Db\Historique();
            $histo->setDebut(new \DateTime())
                    ->setCreateur($user);
            $entity->setHistorique($histo);
            $em->persist($histo);
        }
        
        // horodatage
//        if (null === $histo->getDebut()) {
//            $histo->setDebut(new \DateTime());
//        }
        $histo->setModification(new \DateTime());

//        $e = new \Exception('test');
//        var_dump($e->getTraceAsString());
//        if (null === $histo->getCreateur()) {
//            $histo->setCreateur($user);
//        }
        $histo->setModificateur($user);
    }
    
    /**
     * 
     * @param mixed $identity
     * @return \Common\ORM\Event\Listeners\Histo
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        
        return $this;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getIdentity()
    {
        if (null === $this->identity) {
            $authenticationService = $this->sl->get('Zend\Authentication\AuthenticationService');
            if ($authenticationService->hasIdentity()) {
                $this->identity = $authenticationService->getIdentity();
            }
        }
        
        return $this->identity;
    }    

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::prePersist);
    }
}