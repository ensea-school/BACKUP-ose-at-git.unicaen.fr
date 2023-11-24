<?php

namespace Paiement\Service;


use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of TblPaiementService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class TblPaiementService extends AbstractEntityService
{

    use AnneeServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Paiement\Entity\Db\TblPaiement::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'tbl_p';
    }

    public function finderByHeuresAPayer (QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->where("$alias.heuresAPayerAA > $alias.heuresDemandeesAA OR  $alias.heuresAPayerAC > $alias.heuresDemandeesAC");

        return $qb;

    }
}