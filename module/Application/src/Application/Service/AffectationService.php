<?php

namespace Application\Service;

use Application\Acl\Role as RoleAcl;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Affectation;

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
     * @throws \RuntimeException
     * @return mixed
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
     * @param RoleAcl|null $role
     *
     * @return null|Affectation
     */
    public function getByRole(RoleAcl $role = null)
    {
        $context = $this->getServiceContext();

        if (!$role) {
            $role = $this->getServiceContext()->getSelectedIdentityRole();
        }

        if (!$context->getUtilisateur()) return null;

        $this->getEntityManager()->getFilters()->enable('historique')->init([
            Affectation::class,
        ]);

        $params = [
            'utilisateur' => $context->getUtilisateur(),
            'role'        => $role->getDbRole(),
            'structure'   => $role->getPerimetre()->isComposante() ? $context->getStructure() : null,
        ];
        $affectation = $this->getRepo()->findOneBy($params);

        return $affectation;
    }



    /**
     *
     * @param \Application\Entity\Db\Role|string $role
     * @param QueryBuilder                       $qb
     * @param string                             $alias
     *
     * @return QueryBuilder
     * @todo A REVOIR! ! ! !
     */
    public function finderByRole($role, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if ($role instanceof \Application\Entity\Db\Role) {
            $role = $role->getCode();
        }

        $qb
            ->innerJoin($alias . '.role', $ralias = uniqid('r'))
            ->andWhere("$ralias.code = :code")->setParameter('code', $role);

        return $qb;
    }
}