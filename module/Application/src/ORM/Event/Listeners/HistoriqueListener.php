<?php

namespace Application\ORM\Event\Listeners;


use Administration\Interfaces\ParametreEntityInterface;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use Utilisateur\Service\UtilisateurServiceAwareTrait;

/**
 * Description of HistoriqueListener
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class HistoriqueListener implements EventSubscriber
{
    use ContextServiceAwareTrait;
    use UtilisateurServiceAwareTrait;

    private
    bool $enabled = true;



    /**
     * @param LifecycleEventArgs $args
     *
     * @throws RuntimeException Aucun utilisateur disponible pour en faire l'auteur de la création/modification
     */
    public function updateHistorique(HistoriqueAwareInterface $entity)
    {
        $now = new \DateTime();

        // on tente d'abord d'obtenir l'utilisateur connecté pour en faire l'auteur de la création/modification.
        $user = $this->getServiceContext()->getUtilisateur();
        // si aucun utilisateur connecté n'est disponible, on utilise l'éventuel auteur existant
        if (null === $user) {
            $user = $entity->getHistoCreateur();
        }
        // Si aucun utilisatur n'esty trouvé, alors on utilise OseAppli
        if (null === $user) {
            $user = $this->getServiceUtilisateur()->getOse();
        }
        // si nous ne disposons d'aucun utilisateur, basta!
        if (null === $user) {
            throw new RuntimeException("Aucun utilisateur disponible pour en faire l'auteur de la création/modification.");
        }

        if (null === $entity->getHistoCreation()) {
            $entity->setHistoCreation($now);
        }
        if (null === $entity->getHistoCreateur()) {
            $entity->setHistoCreateur($user);
        }

        $entity->setHistoModificateur($user);
        $entity->setHistoModification($now);

        if (null !== $entity->getHistoDestruction() && null === $entity->getHistoDestructeur()) {
            $entity->setHistoDestructeur($user);
        }
    }



    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($this->isEnabled()) {
            $entity = $args->getEntity();
            // On fait si c'est une HistoriqueAwareInterface, mais pas une ParametreEntityInterface
            if (($entity instanceof HistoriqueAwareInterface) && (!$entity instanceof ParametreEntityInterface)) {
                $this->updateHistorique($entity);
            }
        }
    }



    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        if ($this->isEnabled()) {
            $entity = $args->getEntity();
            // On fait si c'est une HistoriqueAwareInterface, mais pas une ParametreEntityInterface
            if (($entity instanceof HistoriqueAwareInterface) && (!$entity instanceof ParametreEntityInterface)) {
                $this->updateHistorique($entity);
            }
        }
    }



    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate];
    }



    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }



    /**
     * @param bool $enabled
     *
     * @return HistoriqueListener
     */
    public function setEnabled(bool $enabled): HistoriqueListener
    {
        $this->enabled = $enabled;

        return $this;
    }
}