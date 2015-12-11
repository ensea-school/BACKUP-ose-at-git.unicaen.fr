<?php

namespace Application\Service;

/**
 * Description of ModificationServiceDu
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MotifModificationServiceDu extends AbstractEntityService
{


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\MotifModificationServiceDu::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'mmsd';
    }

    public function getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
    {
        list( $qb, $alias ) = $this->initQuery($qb, $alias);
        $qb->addOrderBy($alias.'.libelle');
        return parent::getList($qb, $alias);
    }

}