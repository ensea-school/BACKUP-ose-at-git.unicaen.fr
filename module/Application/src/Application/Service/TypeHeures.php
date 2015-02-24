<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\TypeHeures as TypeHeuresEntity;

/**
 * Description of TypeHeures
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeHeures extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeHeures';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'th';
    }

    /**
     *
     * @param string $code
     * @return \Application\Entity\Db\TypeHeures
     */
    public function getByCode( $code )
    {
        if (null == $code) return null;
        return $this->getRepo()->findOneBy(['code' => $code]);
    }

    /**
     *
     * @param ServiceAPayerInterface $serviceAPayer
     * @return \Application\Entity\Db\TypeHeures[]
     */
    public function getListFromServiceAPayer( ServiceAPayerInterface $serviceAPayer )
    {
        $list = [];
        if ($serviceAPayer->getHeuresComplFi() != 0){
            $th = $this->getByCode(TypeHeuresEntity::FI);
            $list[$th->getId()] = $th;
        }
        if ($serviceAPayer->getHeuresComplFa() != 0){
            $th = $this->getByCode(TypeHeuresEntity::FA);
            $list[$th->getId()] = $th;
        }
        if ($serviceAPayer->getHeuresComplFc() != 0){
            $th = $this->getByCode(TypeHeuresEntity::FC);
            $list[$th->getId()] = $th;
        }
        if ($serviceAPayer->getHeuresComplFcMajorees() != 0){
            $th = $this->getByCode(TypeHeuresEntity::FC_MAJOREES);
            $list[$th->getId()] = $th;
        }
        if ($serviceAPayer->getHeuresComplReferentiel() != 0){
            $th = $this->getByCode(TypeHeuresEntity::REFERENTIEL);
            $list[$th->getId()] = $th;
        }
        return $list;
    }

    /**
     * Retourne la liste des types de formation
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\TypeHeures[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");
        return parent::getList($qb, $alias);
    }

}