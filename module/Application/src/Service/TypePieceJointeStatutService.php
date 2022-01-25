<?php

namespace Application\Service;

use Application\Service\Traits\AnneeServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\Statut;

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



    /**
     * Retourne la liste des enregistrements correspondant aux statut intervenant spécifié.
     *
     * @param Statut            $statut
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStatutIntervenant(Statut $statut, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.statut = :statut")->setParameter('statut', $statut);

        return $qb;
    }



    /**
     * @param TypePieceJointeStatut $typePieceJointeStatut
     *
     * @return \Application\Entity\Db\Annee|null
     */
    public function derniereAnneeDebut(TypePieceJointeStatut $typePieceJointeStatut)
    {
        $id     = $typePieceJointeStatut->getId() ?: 0;
        $statut = $typePieceJointeStatut->getStatutIntervenant()->getId();
        $tpj    = $typePieceJointeStatut->getTypePieceJointe()->getId();
        $annee  = $this->getServiceContext()->getAnnee()->getId();
        $params = compact('id', 'statut', 'tpj', 'annee');

        $sql = "
        SELECT 
          MAX(ANNEE_FIN_ID) ANNEE_FIN
        FROM 
          TYPE_PIECE_JOINTE_STATUT TPJS
        WHERE
          TPJS.HISTO_DESTRUCTION IS NULL
          AND TPJS.STATUT_ID = :statut
          AND TPJS.TYPE_PIECE_JOINTE_ID = :tpj
          AND TPJS.ID <> :id
          AND TPJS.ANNEE_FIN_ID IS NOT NULL
          AND TPJS.ANNEE_FIN_ID < :annee
        ";
        $res = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        if ($res && (int)$res['ANNEE_FIN'] !== 0) {
            return $this->getServiceAnnee()->get((int)$res['ANNEE_FIN']);
        } else {
            return null;
        }
    }



    /**
     * @param TypePieceJointeStatut $typePieceJointeStatut
     *
     * @return \Application\Entity\Db\Annee|null
     */
    public function premiereAnneeFin(TypePieceJointeStatut $typePieceJointeStatut)
    {
        $id     = $typePieceJointeStatut->getId() ?: 0;
        $statut = $typePieceJointeStatut->getStatutIntervenant()->getId();
        $tpj    = $typePieceJointeStatut->getTypePieceJointe()->getId();
        $annee  = $this->getServiceContext()->getAnnee()->getId();
        $params = compact('id', 'statut', 'tpj', 'annee');

        $sql = "
        SELECT 
          MAX(ANNEE_DEBUT_ID) ANNEE_DEBUT
        FROM 
          TYPE_PIECE_JOINTE_STATUT TPJS
        WHERE
          TPJS.HISTO_DESTRUCTION IS NULL
          AND TPJS.STATUT_ID = :statut
          AND TPJS.TYPE_PIECE_JOINTE_ID = :tpj
          AND TPJS.ID <> :id
          AND TPJS.ANNEE_DEBUT_ID IS NOT NULL
          AND TPJS.ANNEE_DEBUT_ID > :annee
        ";
        $res = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        if ($res && (int)$res['ANNEE_DEBUT'] !== 0) {
            return $this->getServiceAnnee()->get((int)$res['ANNEE_DEBUT']);
        } else {
            return null;
        }
    }



    /**
     * @param TypePieceJointeStatut $entity
     *
     * @return TypePieceJointeStatut
     */
    public function save($entity)
    {
        $ddeb = $entity->getAnneeDebut() ? $entity->getAnneeDebut()->getId() : 0;
        $dfin = $entity->getAnneeFin() ? $entity->getAnneeFin()->getId() : 99999;

        if ($dfin < $ddeb) {
            throw new \Exception('L\'année de fin ne peut être antérieure à l\'année de début');
        }

        return parent::save($entity);
    }



    /**
     * Retourne la liste des enregistrements.
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return TypePieceJointeStatut[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return parent::getList($qb, $alias);
    }
}