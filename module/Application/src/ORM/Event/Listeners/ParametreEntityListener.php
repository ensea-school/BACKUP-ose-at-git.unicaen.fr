<?php

namespace Application\ORM\Event\Listeners;

use Administration\Interfaces\ParametreEntityInterface;
use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Annee;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Utilisateur\Service\UtilisateurServiceAwareTrait;

class ParametreEntityListener implements EventSubscriber
{
    use ContextServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use ParametresServiceAwareTrait;

    const REMOVE_DATE = '1066-10-14';


    protected LifecycleEventArgs $args;

    protected EntityManager $em;

    protected ParametreEntityInterface $entity;

    protected bool $isSaving = false;



    public function setEntityManager(EntityManager $entityManager): void
    {
        $this->em = $entityManager;
    }



    protected function save(LifecycleEventArgs $args): void
    {
        if ($this->isSaving) return;

        /* Initialisation */
        $this->args   = $args;
        $this->em     = $args->getObjectManager();
        $this->entity = $args->getObject();

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



    protected function saveNextEntity(): void
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



    protected function deleteHisto(): void
    {
        $key          = $this->extract($this->entity)['key'];
        $key['annee'] = $this->entity->getAnnee();
        /** @var $entity ParametreEntityInterface */
        $entity = $this->repo()->findOneBy($key);
        if ($entity && !$entity->estNonHistorise()) {
            $this->em->remove($entity);
            $this->em->flush($entity);
        }
    }



    protected function deleteNextEntity(): void
    {
        $nextEntity = $this->nextEntity();
        if ($nextEntity && !$this->isManuel($nextEntity)) {
            // si on trouve une entité qui n'a pas été saisie manuellement, alors on la supprime
            $this->em->remove($nextEntity);
            $this->em->flush($nextEntity);
        }
    }



    protected function saveNextEntities(): void
    {
        if ($this->args instanceof PreUpdateEventArgs) {
            $changeSet = $this->args->getEntityChangeSet();
        }else{
            $changeSet = [];
        }

        $data = $this->extract($this->entity, $changeSet);
        unset($data['data']['histoModificateur']);
        unset($data['data']['histoDestruction']);
        unset($data['data']['histoDestructeur']);
        $classname = get_class($this->entity);

        $next = $this->nextEntities($this->entity, $changeSet);
        foreach ($next as $anneeId => $entity) {
            if (null === $entity) {
                $entity = new $classname;
            }
            $annnee                     = $this->em->getRepository(Annee::class)->find($anneeId);
            $entityData                 = $data;
            $entityData['key']['annee'] = $annnee;
            foreach ($entityData['key'] as $k => $v) {
                if ($v instanceof ParametreEntityInterface) {
                    $entityData['key'][$k] = $this->entityAutreAnnee($v, $annnee);
                }
            }
            foreach ($entityData['data'] as $k => $v) {
                if ($v instanceof ParametreEntityInterface) {
                    $entityData['data'][$k] = $this->entityAutreAnnee($v, $annnee);
                }
            }
            $this->hydrate($entityData, $entity);

            $this->em->persist($entity);
            $this->em->flush($entity);
        }
    }



    protected function deleteNextEntities(): void
    {
        $next = $this->nextEntities($this->entity);
        foreach ($next as $entity) {
            if ($entity) {
                $this->em->remove($entity);
                $this->em->flush($entity);
            }
        }
    }



    public function entityAutreAnnee(ParametreEntityInterface $entity, Annee $annee): ?ParametreEntityInterface
    {
        if ($entity->getAnnee() === $annee) {
            return $entity;
        }

        $key          = $this->extract($entity)['key'];
        $key['annee'] = $annee;

        $buff    = $this->em->getRepository(get_class($entity))->findBy($key);
        $bentity = null;
        if (!empty($buff)) {
            foreach ($buff as $bentity) {
                if ($bentity->estNonHistorise()) {
                    return $bentity;
                }
            }
        }

        return $bentity;
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
        if ($this->args instanceof PreUpdateEventArgs) {

            $ecs = $this->args->getEntityChangeSet();

            return isset($ecs['histoDestruction']) && $ecs['histoDestruction'][0] === null && $ecs['histoDestruction'][1] instanceof \DateTime;
        }

        if ($this->args instanceof PreRemoveEventArgs) {
            /** @var ParametreEntityInterface $object */
            $object = $this->args->getObject();

            return $object->getHistoDestruction() && $object->getHistoDestruction()->format('Y-m-d') == self::REMOVE_DATE;
        }

        return false;
    }



    protected function nextEntities(ParametreEntityInterface $entity, array $changeSet = []): array
    {
        $key = $this->extract($entity, $changeSet)['key'];
        unset($key['annee']);

        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from(get_class($entity), 'e');
        $qb->where('e.annee > :annee');
        $qb->setParameter('annee', $entity->getAnnee());
        $pi = 1;
        foreach ($key as $k => $v) {
            if ($v instanceof ParametreEntityInterface) {
                $qb->join('e.' . $k, 'j_' . $k);
                $qb->addSelect('j_' . $k);
                $vkey = $this->extract($v)['key'];
                unset($vkey['annee']);
                foreach ($vkey as $vk => $vv) {
                    $qb->andWhere('j_' . $k . '.' . $vk . ' = :p' . $pi)->setParameter('p' . $pi, $vv);
                    $pi++;
                }
            } else {
                if ($v === null){
                    $qb->andWhere('e.' . $k . ' IS NULL');
                }else{
                    $qb->andWhere('e.' . $k . ' = :p' . $pi)->setParameter('p' . $pi, $v);
                }

                $pi++;
            }
        }
        $query = $qb->getQuery();

        /** @var ParametreEntityInterface[] $nexts */
        /** @var ParametreEntityInterface[] $buff */
        $buff  = $query->getResult();
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
                // si une modif manuelle a été apportée, alors ce n'est plus la suite d'un même entité, mais une autre suite donc on stoppe
                if ($this->isManuel($nexts[$a])) {
                    break;
                }

                $nexta[$a] = $nexts[$a];
            } else {
                $nexta[$a] = null;
            }
        }

        return $nexta;
    }



    protected function nextEntity(): ?ParametreEntityInterface
    {
        if ($this->args instanceof PreUpdateEventArgs) {
            $changeSet = $this->args->getEntityChangeSet();
        }else{
            $changeSet = [];
        }

        $params          = $this->extract($this->entity, $changeSet)['key'];
        $params['annee'] = $this->em->getRepository(Annee::class)->find($this->entity->getAnnee()->getId() + 1);

        return $this->repo()->findOneBy($params);
    }



    protected function extract(ParametreEntityInterface $entity, array $changeSet =  []): array
    {
        $metadata = $this->em->getClassMetadata(get_class($entity));

        /* Récupération de la liste des champs de la clé de l'entité */
        $keyFields = [];
        $tableName = $metadata->table['name'];
        $uniqueConstraintName = $tableName . '_un';
        mpg_upper($uniqueConstraintName);
        if (!isset($metadata->table['uniqueConstraints'][$uniqueConstraintName])) {
            throw new \Exception('Contrainte d\'unicité "' . $tableName . '_UN" non trouvée dans le mapping Doctrine pour la classe ' . get_class($entity));
        }
        $cols = $metadata->table['uniqueConstraints'][$uniqueConstraintName]['columns'];

        foreach ($cols as $consCol) {
            $histoDestructionCol = 'histo_destruction';
            mpg_upper($histoDestructionCol);

            if (!in_array($consCol, [$histoDestructionCol])) {
                if (isset($metadata->fieldNames[$consCol])) {
                    $keyFields[] = $metadata->fieldNames[$consCol];
                } else {
                    foreach ($metadata->associationMappings as $property => $associationMapping) {
                        if (isset($associationMapping['joinColumns']) && $consCol == $associationMapping['joinColumns'][0]['name']) {
                            $keyFields[] = $property;
                        }
                    }
                }
            }
        }


        /* Récupération de la liste des champs de l'entité */
        $dataFields = ['histoDestruction'];
        foreach ($metadata->fieldMappings as $field => $fieldParams) {
            if (!in_array($field, $keyFields) && !in_array($field, ['id'])) {
                $dataFields[] = $field;
            }
        }

        foreach ($metadata->associationMappings as $field => $associationMapping) {
            if (!in_array($field, $keyFields)
                && !in_array($field, ['annee'])
                && $associationMapping['type'] == ClassMetadataInfo::MANY_TO_ONE
            ) {
                $dataFields[] = $field;
            }
        }


        /* Récupération des valeurs des champs */
        $res = [
            'key'  => [],
            'data' => [],
        ];
        foreach ($keyFields as $keyField) {
            if (array_key_exists($keyField, $changeSet) && array_key_exists(0, $changeSet[$keyField])) {
                $res['key'][$keyField] =  $changeSet[$keyField][0];
                if ($changeSet[$keyField][0] !== $changeSet[$keyField][1]){
                    $res['data'][$keyField] = $changeSet[$keyField][1]; // on force la date pour la MAJ
                }
            }elseif (method_exists($entity, $method = 'get' . ucfirst($keyField))) {
                $res['key'][$keyField] = $entity->$method();
            } elseif (method_exists($entity, $method = 'is' . ucfirst($keyField))) {
                $res['key'][$keyField] = $entity->$method();
            } else {
                throw new \Exception('Aucun accesseur trouvé pour le champ ' . $keyField . ' de l\'entité ' . get_class($entity));
            }
        }

        foreach ($dataFields as $dataField) {
            if (method_exists($entity, $method = 'get' . ucfirst($dataField))) {
                $res['data'][$dataField] = $entity->$method();
            } elseif (method_exists($entity, $method = 'is' . ucfirst($dataField))) {
                $res['data'][$dataField] = $entity->$method();
            } else {
                throw new \Exception('Aucun accesseur trouvé pour le champ ' . $keyField . ' de l\'entité ' . get_class($entity));
            }
        }

        return $res;
    }



    protected function hydrate(array $data, ParametreEntityInterface $entity)
    {
        foreach ($data['key'] as $field => $value) {
            $method = 'set' . ucfirst($field);
            if (method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }
        foreach ($data['data'] as $field => $value) {
            $method = 'set' . ucfirst($field);
            if (method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }
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



    protected function enableFilters(array $filters): void
    {
        foreach ($filters as $name => $filter) {
            $this->em->getFilters()->enable($name);
        }
    }



    public function prePersist(PrePersistEventArgs $args): void
    {
        if ($args->getObject() instanceof ParametreEntityInterface) {
            $this->save($args);
        }
    }



    public function preUpdate(PreUpdateEventArgs $args): void
    {
        if ($args->getObject() instanceof ParametreEntityInterface) {
            $this->save($args);
        }
    }



    public function preRemove(PreRemoveEventArgs $args): void
    {
        if ($args->getObject() instanceof ParametreEntityInterface) {
            $args->getObject()->setHistoDestruction(new \DateTime(self::REMOVE_DATE));
            $this->save($args);
        }
    }



    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate, Events::preRemove];
    }
}