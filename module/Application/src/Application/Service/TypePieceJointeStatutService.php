<?php

namespace Application\Service;

use Application\Service\Traits\AnneeServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;

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
     * @param TypePieceJointeStatut $typePieceJointeStatut
     *
     * @return \Application\Entity\Db\Annee|null
     */
    public function derniereAnneeDebut(TypePieceJointeStatut $typePieceJointeStatut)
    {
        $id = $typePieceJointeStatut->getId() ?: 0;
        $statut = $typePieceJointeStatut->getStatutIntervenant()->getId();
        $tpj = $typePieceJointeStatut->getTypePieceJointe()->getId();
        $annee = $this->getServiceContext()->getAnnee()->getId();
        $params = compact('id','statut','tpj','annee');

        $sql = "
        SELECT 
          MAX(annee_fin_id) annee_fin
        FROM 
          type_piece_jointe_statut tpjs
        WHERE
          tpjs.histo_destruction IS NULL
          AND tpjs.statut_intervenant_id = :statut
          AND tpjs.type_piece_jointe_id = :tpj
          AND tpjs.id <> :id
          AND tpjs.annee_fin_id IS NOT NULL
          AND tpjs.annee_fin_id < :annee
        ";
        $res    = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        if ($res && (int)$res['ANNEE_FIN'] !== 0){
            return $this->getServiceAnnee()->get((int)$res['ANNEE_FIN']);
        }else{
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
        $id = $typePieceJointeStatut->getId() ?: 0;
        $statut = $typePieceJointeStatut->getStatutIntervenant()->getId();
        $tpj = $typePieceJointeStatut->getTypePieceJointe()->getId();
        $annee = $this->getServiceContext()->getAnnee()->getId();
        $params = compact('id','statut','tpj','annee');

        $sql = "
        SELECT 
          MAX(annee_debut_id) annee_debut
        FROM 
          type_piece_jointe_statut tpjs
        WHERE
          tpjs.histo_destruction IS NULL
          AND tpjs.statut_intervenant_id = :statut
          AND tpjs.type_piece_jointe_id = :tpj
          AND tpjs.id <> :id
          AND tpjs.annee_debut_id IS NOT NULL
          AND tpjs.annee_debut_id > :annee
        ";
        $res    = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        if ($res && (int)$res['ANNEE_DEBUT'] !== 0){
            return $this->getServiceAnnee()->get((int)$res['ANNEE_DEBUT']);
        }else{
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
          AND (
               :ddeb BETWEEN COALESCE(tpjs.annee_debut_id,0) AND COALESCE(tpjs.annee_fin_id,99999)
            OR :dfin BETWEEN COALESCE(tpjs.annee_debut_id,0) AND COALESCE(tpjs.annee_fin_id,99999)
          )
        ";

        $ddeb = $entity->getAnneeDebut() ? $entity->getAnneeDebut()->getId() : 0;
        $dfin = $entity->getAnneeFin() ? $entity->getAnneeFin()->getId() : 99999;

        if ($dfin < $ddeb) {
            throw new \Exception('L\'année de fin ne peut être antérieure à l\'année de début');
        }

        $params = [
            'statutIntervenant' => $entity->getStatutIntervenant()->getId(),
            'typePieceJointe'   => $entity->getTypePieceJointe()->getId(),
            'ddeb'              => $ddeb,
            'dfin'              => $dfin,
            'tpjsId'            => (int)$entity->getId(),
        ];
        $res    = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();
        $no = ($res['CC'] > 0);

        if ($no){
             throw new \Exception('La règle de gestion de pièce justificative ne peut pas être appliquée, car elle en chevauche une autre');
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
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return parent::getList($qb, $alias);
    }
}