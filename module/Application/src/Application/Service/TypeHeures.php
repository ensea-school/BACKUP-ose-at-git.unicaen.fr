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
        return TypeHeuresEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'th';
    }



    /**
     *
     * @param string $code
     *
     * @return \Application\Entity\Db\TypeHeures
     */
    public function getByCode($code)
    {
        if (null == $code) return null;

        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    public function finderByServiceaPayer(ServiceAPayerInterface $serviceAPayer, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $codes = [];

        $misesEnPaiement = $serviceAPayer->getMiseEnPaiement();
        foreach ($misesEnPaiement as $miseEnPaiement) {
            /* @var $miseEnPaiement \Application\Entity\Db\MiseEnPaiement */
            $th                    = $miseEnPaiement->getTypeHeures();
            $codes[$th->getCode()] = $th->getCode();
        }

        if ($serviceAPayer->getHeuresComplFi() != 0) $codes[TypeHeuresEntity::FI] = TypeHeuresEntity::FI;
        if ($serviceAPayer->getHeuresComplFa() != 0) $codes[TypeHeuresEntity::FA] = TypeHeuresEntity::FA;
        if ($serviceAPayer->getHeuresComplFc() != 0) $codes[TypeHeuresEntity::FC] = TypeHeuresEntity::FC;
        if ($serviceAPayer->getHeuresComplFcMajorees() != 0) $codes[TypeHeuresEntity::FC_MAJOREES] = TypeHeuresEntity::FC_MAJOREES;
        if ($serviceAPayer->getHeuresComplReferentiel() != 0) $codes[TypeHeuresEntity::REFERENTIEL] = TypeHeuresEntity::REFERENTIEL;
        $this->finderByCode($codes, $qb, $alias);

        return $qb;
    }



    /**
     * Retourne la liste des types de formation
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return \Application\Entity\Db\TypeHeures[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return parent::getList($qb, $alias);
    }

}