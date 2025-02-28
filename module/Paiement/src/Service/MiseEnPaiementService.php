<?php

namespace Paiement\Service;

use Application\Entity\Db\Periode;
use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\TypeIntervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\MiseEnPaiementRecherche;

/**
 * Description of MiseEnPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementService extends AbstractEntityService
{
    use IntervenantServiceAwareTrait;
    use MiseEnPaiementIntervenantStructureServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use TblPaiementServiceAwareTrait;
    use \Workflow\Service\WorkflowServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return MiseEnPaiement::class;
    }



    public function getAlias(): string
    {
        return 'mep';
    }



    /**
     * Retourne les mises en paiement prêtes à payer (c'est-à-dire validées et non déjà payées
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByEtat($etat, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        switch ($etat) {
            case MiseEnPaiement::A_METTRE_EN_PAIEMENT:
                $qb->andWhere("$alias.dateMiseEnPaiement IS NULL");
                break;
            case MiseEnPaiement::MIS_EN_PAIEMENT:
                $qb->andWhere("$alias.dateMiseEnPaiement IS NOT NULL");
                break;
        }

        return $qb;
    }



    public function finderByTypeIntervenant(TypeIntervenant $typeIntervenant = null, QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        [$qb, $alias] = $this->initQuery($qb, $alias);

        if ($typeIntervenant) {
            $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
            $serviceMIS->join($this->getServiceIntervenant(), $qb, 'intervenant', false);
            $this->getServiceIntervenant()->finderByType($typeIntervenant, $qb);
        }

        return $qb;
    }



    public function finderByStructure(?Structure $structure, ?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->finderByStructure($structure, $qb);

        return $qb;
    }



    public function finderByIntervenants($intervenants, QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->finderByIntervenant($intervenants, $qb);

        return $qb;
    }



    /**
     * Retourne les données du TBL des mises en paiement en fonction des critères de recherche transmis
     *
     * @param MiseEnPaiementRecherche $recherche
     *
     * @return array
     */
    public function getEtatPaiement(MiseEnPaiementRecherche $recherche, array $options = [])
    {
        // initialisation
        $defaultOptions = [
            'composante' => null,            // Composante qui en fait la demande
        ];
        $options        = array_merge($defaultOptions, $options);
        $annee          = $this->getServiceContext()->getAnnee();

        $defaultTotal = [
            'hetd'                => 0,
            'hetd-pourc'          => 0,
            'hetd-montant'        => 0,
            'rem-fc-d714'         => 0,
            'exercice-aa'         => 0,
            'exercice-aa-montant' => 0,
            'exercice-ac'         => 0,
            'exercice-ac-montant' => 0,
        ];
        $data         = [
            'total' => $defaultTotal,
        ];

        // requêtage
        $conditions = [
            'annee_id = ' . $annee->getId(),
        ];

        if ($t = $recherche->getTypeIntervenant()) {
            $conditions['type_intervenant_id'] = 'type_intervenant_id = ' . $t->getId();
        }
        if ($e = $recherche->getEtat()) {
            $conditions['etat'] = 'etat = \'' . $e . '\'';
        }
        if ($p = $recherche->getPeriode()) {
            $conditions['periode_id'] = 'periode_id = ' . $p->getId();
        }
        if ($s = $recherche->getStructure()) {
            $conditions['structure_id'] = 'structure_id = ' . $s->getId();
        }
        if ($recherche->getIntervenants()->count() > 0) {
            $iIdList = [];
            foreach ($recherche->getIntervenants() as $intervenant) {
                $iIdList[] = $intervenant->getId();
            }
            $conditions['intervenant_id'] = 'intervenant_id IN (' . implode(',', $iIdList) . ')';
        }

        if ($options['composante'] instanceof Structure) {
            $conditions['composante'] = "structure_id = " . (int)$options['composante']->getId();
        }

        $sql  = 'SELECT * FROM V_ETAT_PAIEMENT WHERE ' . implode(' AND ', $conditions) . ' ORDER BY INTERVENANT_NOM, CENTRE_COUT_CODE';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        // récupération des données
        while ($d = $stmt->fetch()) {
            $ds = [
                'annee-libelle' => (string)$annee,

                'intervenant-code'         => $d['INTERVENANT_CODE'],
                'intervenant-nom'          => $d['INTERVENANT_NOM'],
                'intervenant-numero-insee' => (string)$d['INTERVENANT_NUMERO_INSEE'],

                'centre-cout-code'            => $d['CENTRE_COUT_CODE'],
                'domaine-fonctionnel-libelle' => $d['DOMAINE_FONCTIONNEL_LIBELLE'],

                'taux-remu'         => $d['TAUX_REMU'],
                'taux-horaire'      => (float)$d['TAUX_HORAIRE'],
                'taux-conges-payes' => (float)$d['TAUX_CONGES_PAYES'],

                'hetd'                => (float)$d['HETD'],
                'hetd-pourc'          => (float)$d['HETD_POURC'],
                'hetd-montant'        => (float)$d['HETD_MONTANT'],
                'rem-fc-d714'         => (float)$d['REM_FC_D714'],
                'exercice-aa'         => (float)$d['EXERCICE_AA'],
                'exercice-aa-montant' => (float)$d['EXERCICE_AA_MONTANT'],
                'exercice-ac'         => (float)$d['EXERCICE_AC'],
                'exercice-ac-montant' => (float)$d['EXERCICE_AC_MONTANT'],
            ];
            if ($ds['taux-conges-payes'] != 1) {
                //on a des congés payés
                $data['total']['conges-payes'] = true;
            }

            $iid = $d['INTERVENANT_ID'];

            /* Initialisation éventuelle */
            if (!isset($data[$iid])) {
                $data[$iid] = [
                    'hetd'        => [
                        'total' => $defaultTotal,
                    ],
                    'rem-fc-d714' => [
                        'total' => $defaultTotal,
                    ],
                ];
            }

            /* Calcul des totaux */
            foreach ($defaultTotal as $col => $null) {
                $data['total'][$col] += $ds[$col];
            }
            if ($ds['hetd'] > 0) {
                $data[$iid]['hetd'][] = $ds;
                foreach ($defaultTotal as $col => $null) {
                    $data[$iid]['hetd']['total'][$col] += $ds[$col];
                }
            }
            if ($ds['rem-fc-d714'] > 0) {
                $data[$iid]['rem-fc-d714'][] = $ds;
                foreach ($defaultTotal as $col => $null) {
                    $data[$iid]['rem-fc-d714']['total'][$col] += $ds[$col];
                }
            }
        }

        return $data;
    }




    /**
     *
     * @param Structure $structure
     * @param \Intervenant\Entity\Db\Intervenant[] $intervenants
     * @param Periode $periodePaiement
     * @param \DateTime $dateMiseEnPaiement
     */
    public function mettreEnPaiement(Structure $structure, $intervenants, Periode $periodePaiement, \DateTime $dateMiseEnPaiement)
    {
        [$qb, $alias] = $this->initQuery();
        $this->finderByEtat(MiseEnPaiement::A_METTRE_EN_PAIEMENT, $qb);
        $this->finderByStructure($structure, $qb);
        $this->finderByIntervenants($intervenants, $qb);
        $mepList = $this->getList($qb);
        foreach ($mepList as $mep) {
            /* @var $mep MiseEnPaiement */
            $mep->setPeriodePaiement($periodePaiement);
            $mep->setDateMiseEnPaiement($dateMiseEnPaiement);
            $this->save($mep);
        }
    }

}
