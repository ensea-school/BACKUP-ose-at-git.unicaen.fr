<?php

namespace Application\Service;

use Application\Entity\Db\Role;
use Application\Provider\Role\RoleProvider;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Entity\Db\Affectation;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;

/**
 * Description of AffectationService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Affectation get($id)
 * @method Affectation[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Affectation newEntity()
 */
class AffectationService extends AbstractEntityService
{
    use SourceServiceAwareTrait;
    use StructureServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Affectation::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'aff';
    }



    /**
     * Sauvegarde une entité
     *
     * @param Affectation $entity
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        $structure = $this->getServiceContext()->getSelectedIdentityRole()->getStructure();
        if ($structure && $entity->getStructure() != $structure) {
            throw new \LogicException('Vous n\'avez pas le droit de modifier une affectation d\'une structure autre que la vôtre.');
        }
        if (!$entity->getSource()) {
            $entity->setSource($this->getServiceSource()->getOse());
        }

        return parent::save($entity);
    }



    /**
     * Hack pour gérer le finder de structure différent des autres compte tenu de la hiérarchisation des structures
     */

    public function finderByRole(Role $role, ?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        if ($role) {
            $qb->join("$alias.role", 'r');
            $qb->andWhere("$alias.role = :role");
            $qb->setParameter('role', $role);
        }

        return $qb;
    }



    public function finderByStructure(?Structure $structure, ?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $structureService = $this->getServiceStructure();
        $structureAlias   = $structureService->getAlias();

        $this->join($structureService, $qb, 'structure');

        $qb->andWhere("$structureAlias = :structure")->setParameter('structure', $structure);

        return $qb;
    }



    public function deleteCacheAffectation():void
    {
        $em = $this->getEntityManager();

        $cache = $em->getConfiguration()->getResultCache();
        $cache->deleteItem(str_replace('\\', '_', RoleProvider::class) . '_affectations');
    }

}