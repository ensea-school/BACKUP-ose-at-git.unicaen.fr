<?php

namespace PieceJointe\Service;

use Application\Service\AbstractEntityService;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use PieceJointe\Entity\Db\TypePieceJointeStatut;

/**
 * Description of TypePieceJointeStatut
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypePieceJointeStatutService extends AbstractEntityService
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
        return TypePieceJointeStatut::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tpjs';
    }



    public function incrementerNumPiece(TypePieceJointeStatut $typePieceJointeStatut)
    {
        $sql      = "SELECT TPJS_NUM_REGLE_SEQ.NEXTVAL S FROM dual";
        $numPiece = (int)$this->getEntityManager()->getConnection()->executeQuery($sql)->fetchOne();
        $typePieceJointeStatut->setNumRegle($numPiece);
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.id");

        return $qb;
    }

}