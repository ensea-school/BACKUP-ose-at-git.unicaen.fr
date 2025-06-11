<?php

namespace Paiement\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Service\Traits\TypeHeuresServiceAwareTrait;
use Paiement\Entity\Db\CentreCout;


/**
 * Description of CentreCout
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCoutService extends AbstractEntityService
{
    use TypeHeuresServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass ()
    {
        return CentreCout::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias ()
    {
        return 'cc';
    }



    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param TypeHeures        $typeHeures
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByTypeHeures (TypeHeures $typeHeures, ?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($this->getServiceTypeHeures(), $qb, 'typeHeures', false, $alias);
        $qb->andWhere($this->getServiceTypeHeures()->getAlias() . ' = :typeHeures');
        $qb->setParameter('typeHeures', $typeHeures);


        return $qb;
    }



    /**
     * Formatte une liste d'entités CentreCout (centres de coûts et éventuels EOTP fils)
     * en tableau attendu par l'aide de vue FormSelect.
     *
     * NB: la liste en entrée doit être triées par code parent (éventuel) PUIS par code.
     *
     * @param CentreCout[] $centresCouts
     */
    public function formatCentresCouts ($centresCouts)
    {
        $result = [];

        foreach ($centresCouts as $cc) {

            try {
                $id       = $cc->getId();
                $ccp      = $cc->getParent() ?: null;
                $idParent = null;
                if ($ccp) {
                    $ccp->estHistorise();
                    $idParent = $ccp->getId();
                }
            } catch (EntityNotFoundException $e) {
                //On catch l'exception dans le cas d'un centre de cout dont le parent a été historisé...
                $idParent = null;
            }


            if ($idParent) {
                $result[$idParent]['label']        = (string)$ccp;
                $result[$idParent]['options'][$id] = (string)$cc;
            } else {
                $result[$id]['label']        = (string)$cc;
                $result[$id]['options'][$id] = (string)$cc;
            }
        }

        // parcours pour supprimer le niveau 2 lorsque le centre de coûts n'a pas d'EOTP fils
        foreach ($result as $id => $data) {
            if (isset($data['options']) && count($data['options']) === 1) {
                $result[$id] = $data['label'];
            }
        }

        ksort($result);

        return $result;
    }



    public function getCentresCoutsMiseEnPaiement (Structure $structure): array
    {
        $sql = "
        SELECT 
            cc.id 		 				 centre_cout_id,
            cc.libelle	  				 libelle,
            cc.code      				 code,
            CASE WHEN tr.fi = 1 AND cca.fi = 1 THEN 1 ELSE 0 END fi,
            CASE WHEN tr.fa = 1 AND cca.fa = 1 THEN 1 ELSE 0 END fa,
            CASE WHEN tr.fc = 1 AND cca.fc = 1 THEN 1 ELSE 0 END fc,
            CASE WHEN tr.referentiel = 1 AND cca.referentiel = 1 THEN 1 ELSE 0 END referentiel,
            CASE WHEN tr.primes = 1 AND cca.primes = 1 THEN 1 ELSE 0 END primes,
            CASE WHEN tr.mission = 1 AND cca.mission = 1 THEN 1 ELSE 0 END mission,
            1                                                           enseignement,
            CASE WHEN tr.code = 'ressources-propres' THEN 1 ELSE 0 END  ressources_propres,
            CASE WHEN tr.code = 'paie-etat' THEN 1 ELSE 0 END  paie_etat,
            cc.parent_id  				 centre_cout_parent_id,
            ccp.libelle   				 libelle_parent,
            ccp.code      				 code_parent
        FROM centre_cout cc 
        LEFT JOIN centre_cout ccp ON ccp.id = cc.parent_id
        JOIN centre_cout_structure ccs ON cc.id = ccs.centre_cout_id 
        JOIN structure s ON s.id = ccs.structure_id
        JOIN cc_activite cca ON cca.id = cc.activite_id
        JOIN type_ressource tr ON tr.id = cc.type_ressource_id
        WHERE s.code = :structure
        AND cc.histo_destruction IS NULL
        ORDER BY ccp.code ASC, cc.code ASC
        ";

        $cc = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, [
            'structure' => $structure->getCode(),
        ]);


        return $cc;
    }



    /**
     * Retourne la liste des centres de coûts sans parent
     *
     * @return CentreCout[]
     */
    public function getListeParent (?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->where("$alias.parent is Null");
        $qb->andWhere("$alias.histoDestruction is Null");
        $this->orderBy($qb);
        $entities    = $qb->getQuery()->execute();
        $result      = [];
        $entityClass = $this->getEntityClass();
        foreach ($entities as $entity) {
            if ($entity instanceof $entityClass) {
                $result[$entity->getId()] = $entity;
            }
        }

        return $result;
    }
}