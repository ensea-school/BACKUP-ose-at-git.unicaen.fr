<?php

namespace Application\ORM\Event\Listeners;

use Application\Entity\Db\Annee;
use Application\Interfaces\ParametreEntityInterface;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Laminas\Hydrator\ClassMethodsHydrator;

class ParametreEntityListener implements EventSubscriber
{
    use ContextServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use ParametresServiceAwareTrait;


    protected LifecycleEventArgs       $args;

    protected EntityManager            $em;

    protected ParametreEntityInterface $entity;

    protected ClassMethodsHydrator     $hydrator;

    protected bool                     $isSaving = false;



    protected function save(LifecycleEventArgs $args)
    {
        if ($this->isSaving) return;

        /* Initialisation */
        $this->args     = $args;
        $this->em       = $args->getEntityManager();
        $this->entity   = $args->getEntity();
        $this->hydrator = new ClassMethodsHydrator();
        $this->hydrator->setUnderscoreSeparatedKeys(false);
        $disabledFilters = $this->disableFilters();

        /* Gestion de l'historique en délégation à l'HistoriqueListener */
        $histoListener = new HistoriqueListener();
        $histoListener->updateHistorique($this->entity);

        if (!$this->entity->getAnnee()) {
            $this->entity->setAnnee($this->getServiceContext()->getAnnee());
        }

        $this->isSaving = true;

        if ($this->inserting()) {
            $this->deleteHisto();
        }

        if ($this->oldAnnee()) {
            if ($this->deleting()) {
                $this->deleteNextEntity();
            } else {
                $this->saveNextEntity();
            }
        } else {
            if ($this->deleting()) {
                $this->deleteNextEntities();
            } else {
                $this->saveNextEntities();
            }
        }

        $this->enableFilters($disabledFilters);

        $this->isSaving = false;
    }



    protected function oldAnnee(): bool
    {
        $anneeCourante = (int)$this->getServiceParametres()->get('annee');

        return $this->entity->getAnnee()->getId() < $anneeCourante;
    }



    protected function saveNextEntity()
    {
        $nextEntity = $this->nextEntity();
        if ($nextEntity && !$this->isManuel($nextEntity)) {
            // si on trouve une entité qui n'a pas été saisie manuellement, alors on la rend manuellement saisie
            $nextEntity->setHistoModificateur($this->entity->getHistoModificateur());
            $nextEntity->setHistoModification($this->entity->getHistoModification());
            $this->em->persist($nextEntity);
            $this->em->flush($nextEntity);
        }
    }



    protected function deleteHisto()
    {
        $key          = $this->key($this->entity);
        $key['annee'] = $this->entity->getAnnee();
        /** @var $entity ParametreEntityInterface */
        $entity = $this->repo()->findOneBy($key);
        if ($entity && !$entity->estNonHistorise()) {
            $this->em->remove($entity);
            $this->em->flush($entity);
        }
    }



    protected function deleteNextEntity()
    {
        $nextEntity = $this->nextEntity();
        if ($nextEntity && !$this->isManuel($nextEntity)) {
            // si on trouve une entité qui n'a pas été saisie manuellement, alors on la supprime
            $this->em->remove($nextEntity);
            $this->em->flush($nextEntity);
        }
    }



    protected function saveNextEntities()
    {
        $data = $this->hydrator->extract($this->entity);
        unset($data['annee']);
        unset($data['histoModificateur']);
        unset($data['histoDestruction']);
        unset($data['histoDestructeur']);
        unset($data['id']);
        $classname = get_class($this->entity);

        $next = $this->nextEntities($this->entity);
        foreach ($next as $anneeId => $entity) {
            if (null === $entity) {
                $entity              = new $classname;
                $entityData          = $data;
                $entityData['annee'] = $this->annee($anneeId);
                $this->hydrator->hydrate($entityData, $entity);
            } else {
                $this->hydrator->hydrate($data, $entity);
            }
        }

        foreach ($next as $anneeId => $entity) {
            $this->em->persist($entity);
            $this->em->flush($entity);
        }
    }



    protected function deleteNextEntities()
    {
        $next = $this->nextEntities($this->entity);
        foreach ($next as $entity) {
            if ($entity) {
                $this->em->remove($entity);
                $this->em->flush($entity);
            }
        }
    }



    protected function annee(int $anneeId): Annee
    {
        return $this->em->getRepository(Annee::class)->find($anneeId);
    }



    protected function isManuel(ParametreEntityInterface $entity): bool
    {
        return $entity->getHistoModificateur() || $entity->getHistoDestruction();
    }



    protected function inserting(): bool
    {
        return null === $this->entity->getId();
    }



    protected function deleting(): bool
    {
        if (!$this->args instanceof PreUpdateEventArgs) return false;

        $ecs = $this->args->getEntityChangeSet();

        return isset($ecs['histoDestruction']) && $ecs['histoDestruction'][0] === null && $ecs['histoDestruction'][1] instanceof \DateTime;
    }



    protected function nextEntities(ParametreEntityInterface $entity, bool $stopManuel = true): array
    {
        $repo = $this->em->getRepository(get_class($entity));

        /** @var ParametreEntityInterface[] $nexts */
        /** @var ParametreEntityInterface[] $buff */
        $buff  = $repo->findBy($this->key($entity), ['annee' => 'asc']);
        $nexts = [];
        foreach ($buff as $bentity) {
            $aid = $bentity->getAnnee()->getId();

            // Si jamais on a déjà trouvé l'entité et qu'elle n'est pas historisée, alors on la garde, sinon on peut remplacer
            if (!(isset($nexts[$aid]) && $nexts[$aid]->estNonHistorise())) {
                $nexts[$aid] = $bentity;
            }
        }

        $nexta = [];
        for ($a = $entity->getAnnee()->getId() + 1; $a <= Annee::MAX; $a++) {

            if (isset($nexts[$a])) {
                $next = $nexts[$a];

                // si une modif manuelle a été apportée, alors ce n'est plus la suite d'un même entité, mais une autre suite donc on stoppe
                if ($stopManuel && $this->isManuel($next)) {
                    break;
                }

                $nexta[$a] = $next;
            } else {
                $nexta[$a] = null;
            }
        }

        return $nexta;
    }



    protected function nextEntity(): ?ParametreEntityInterface
    {
        $params          = $this->key($this->entity);
        $params['annee'] = $this->annee($this->entity->getAnnee()->getId() + 1);

        return $this->repo()->findOneBy($params);
    }



    protected function key(object $entity): array
    {
        $key = [];

        $cols = $this->keyProperties($entity);

        $data = $this->hydrator->extract($entity);
        foreach ($cols as $col) {
            $key[$col] = $data[$col];
        }

        return $key;
    }



    protected function keyProperties(string|object $entity): array
    {
        if (is_object($entity)) {
            $entity = get_class($entity);
        }
        $metadata = $this->em->getClassMetadata($entity);

        $tableName = $metadata->table['name'];

        if (!isset($metadata->table['uniqueConstraints'][$tableName . '_UN'])) {
            throw new \Exception('Contrainte d\'unicité non trouvée');
        }

        $cols       = $metadata->table['uniqueConstraints'][$tableName . '_UN']['columns'];
        $properties = [];
        foreach ($cols as $i => $consCol) {
            if (!in_array($consCol, ['ANNEE_ID', 'HISTO_DESTRUCTION'])) {
                if (isset($metadata->fieldNames[$consCol])) {
                    $properties[] = $metadata->fieldNames[$consCol];
                } else {
                    foreach ($metadata->associationMappings as $property => $associationMapping) {
                        if ($consCol == $associationMapping['joinColumns'][0]['name']) {
                            $properties[] = $property;
                        }
                    }
                }
            }
        }

        return $properties;
    }



    protected function repo(): EntityRepository
    {
        return $this->em->getRepository(get_class($this->entity));
    }



    protected function disableFilters()
    {
        $filters = $this->em->getFilters()->getEnabledFilters();

        foreach ($filters as $name => $filter) {
            $this->em->getFilters()->disable($name);
        }

        return $filters;
    }



    protected function enableFilters(array $filters)
    {
        foreach ($filters as $name => $filter) {
            $this->em->getFilters()->enable($name);
        }
    }



    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof ParametreEntityInterface) {
            $this->save($args);
        }
    }



    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        if ($args->getEntity() instanceof ParametreEntityInterface) {
            $this->save($args);
        }
    }



    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof ParametreEntityInterface) {
            $this->save($args);
        }
    }



    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate, Events::preRemove];
    }
}