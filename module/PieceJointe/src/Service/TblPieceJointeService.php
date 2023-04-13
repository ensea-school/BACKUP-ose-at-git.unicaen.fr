<?php

namespace PieceJointe\Service;

use Application\Service\AbstractEntityService;
use PieceJointe\Entity\Db\TblPieceJointe;

/**
 * Description of TblPieceJointeService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method TblPieceJointe get($id)
 * @method TblPieceJointe[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method TblPieceJointe newEntity()
 *
 */
class TblPieceJointeService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TblPieceJointe::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tblpj';
    }

}