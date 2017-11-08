<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypePieceJointeStatut as TypePieceJointeStatutEntity;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;

/**
 * Description of TypePieceJointeStatut
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypePieceJointeStatut extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypePieceJointeStatutEntity::class;
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
     * @param StatutIntervenantEntity $statut
     * @param QueryBuilder|null       $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStatutIntervenant(StatutIntervenantEntity $statut, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.statut = :statut")->setParameter('statut', $statut);

        return $qb;
    }



    /**
     * Retourne la liste des enregistrements correspondant au témoin de premier recrutement spécifié.
     *
     * @param bool              $premierRecrutement
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByPremierRecrutement($premierRecrutement, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.premierRecrutement = :flag")->setParameter('flag', $premierRecrutement);

        return $qb;
    }



    /**
     * @param TypePieceJointeStatutEntity $entity
     *
     * @return TypePieceJointeStatutEntity
     */
    public function save($entity)
    {
        $sql = "
        SELECT 
          count(*) cc
        FROM 
          type_piece_jointe_statut tpjs
        WHERE
          tpjs.histo_destruction IS NULL
          AND tpjs.statut_intervenant_id = :statutIntervenant
          AND tpjs.type_piece_jointe_id = :typePieceJointe
          AND tpjs.id <> :tpjsId
          AND ((COALESCE(tpjs.annee_fin_id,99999) >= :ddeb) OR (COALESCE(tpjs.annee_debut_id,0) <= :dfin) )
        ";

        $ddeb = $entity->getAnneeDebut() ? $entity->getAnneeDebut()->getId() : 0;
        $dfin = $entity->getAnneeFin() ? $entity->getAnneeFin()->getId() : 99999;

        if ($dfin < $ddeb) {
            throw new \Exception('L\'année de fin ne peut être antérieure à l\'année de début' . $dfin . ':' . $ddeb);
        }

        $params = [
            'statutIntervenant' => $entity->getStatutIntervenant()->getId(),
            'typePieceJointe'   => $entity->getTypePieceJointe()->getId(),
            'ddeb'              => $ddeb,
            'dfin'              => $dfin,
            'tpjsId'            => (int)$entity->getId(),
        ];
        $res    = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        //$no = ($res['cc'] > 0);

        /* if ($no){
             throw new \Exception('La règle de gestion de pièce justificative ne peut pas être appliquée, car elle en chevauche une autre');
         }*/

        return parent::save($entity);
    }



    /**
     * Retourne la liste des enregistrements.
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return TypePieceJointeStatutEntity[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return parent::getList($qb, $alias);
    }
}