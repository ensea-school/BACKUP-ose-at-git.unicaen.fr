<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use OffreFormation\Entity\Db\TypeHeures;
use Paiement\Entity\Db\ServiceAPayerInterface;

/**
 * Description of TypeHeures
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeHeuresService extends AbstractEntityService
{
    use ParametresServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeHeures::class;
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
     * @return \OffreFormation\Entity\Db\TypeHeures
     */
    public function getByCode($code)
    {
        if (null == $code) return null;

        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    public function finderByServiceaPayer(ServiceAPayerInterface $serviceAPayer, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $codes = [];

        $misesEnPaiement = $serviceAPayer->getMiseEnPaiement();
        foreach ($misesEnPaiement as $miseEnPaiement) {
            /* @var $miseEnPaiement \Paiement\Entity\Db\MiseEnPaiement */
            $th                    = $miseEnPaiement->getTypeHeures();
            $codes[$th->getCode()] = $th->getCode();
        }

        if ($this->getServiceParametres()->get('distinction_fi_fa_fc') == '1') {
            if ($serviceAPayer->getHeuresComplFi() != 0) $codes[TypeHeures::FI] = TypeHeures::FI;
            if ($serviceAPayer->getHeuresComplFa() != 0) $codes[TypeHeures::FA] = TypeHeures::FA;
            if ($serviceAPayer->getHeuresComplFc() != 0) $codes[TypeHeures::FC] = TypeHeures::FC;
            if ($serviceAPayer->getHeuresPrimes() != 0) $codes[TypeHeures::PRIMES] = TypeHeures::PRIMES;
        } else {
            $hc = $serviceAPayer->getHeuresComplFi()
                + $serviceAPayer->getHeuresComplFa()
                + $serviceAPayer->getHeuresComplFc()
                + $serviceAPayer->getHeuresPrimes();
            if ($hc != 0) {
                $codes[TypeHeures::FI]          = TypeHeures::ENSEIGNEMENT;
                $codes[TypeHeures::FA]          = TypeHeures::ENSEIGNEMENT;
                $codes[TypeHeures::FC]          = TypeHeures::ENSEIGNEMENT;
                $codes[TypeHeures::PRIMES] = TypeHeures::ENSEIGNEMENT;
            }
        }
        if ($serviceAPayer->getHeuresComplReferentiel() != 0) $codes[TypeHeures::REFERENTIEL] = TypeHeures::REFERENTIEL;
        if ($serviceAPayer->getHeuresMission() != 0) $codes[TypeHeures::MISSION] = TypeHeures::MISSION;
        $this->finderByCode($codes, $qb, $alias);

        return $qb;
    }



    /**
     * Retourne la liste des types de formation
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return \OffreFormation\Entity\Db\TypeHeures[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return parent::getList($qb, $alias);
    }

}