<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Agrement as AgrementEntity;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Rule\Intervenant\AgrementFourniRule;

/**
 * Description of Agrement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Agrement extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return AgrementEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'a';
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");
        return $qb;
    }
    
    /**
     * @return NecessiteAgrementRule
     */
    public function getRuleNecessiteAgrement()
    {
        return $this->getServiceLocator()->get('NecessiteAgrementRule');
    }
    
    /**
     * @return AgrementFourniRule
     */
    public function getRuleAgrementFourni()
    {
        return $this->getServiceLocator()->get('AgrementFourniRule');
    }
}