<?php

namespace Application\Service;

use Application\Service\Traits\SourceServiceAwareTrait;
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

}