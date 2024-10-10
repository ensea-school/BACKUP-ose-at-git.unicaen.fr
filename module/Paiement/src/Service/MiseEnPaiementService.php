<?php

namespace Paiement\Service;

use Application\Entity\Db\Periode;
use Application\Service\AbstractEntityService;
use Application\Service\Traits;
use Doctrine\ORM\QueryBuilder;
use Formule\Entity\Db\FormuleResultatService;
use Formule\Entity\Db\FormuleResultatServiceReferentiel;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Lieu\Entity\Db\Structure;
use Mission\Service\MissionServiceAwareTrait;
use OffreFormation\Service\Traits\TypeHeuresServiceAwareTrait;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\TblPaiement;
use Paiement\Entity\MiseEnPaiementRecherche;
use RuntimeException;

/**
 * Description of MiseEnPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementService extends AbstractEntityService
{
    use \Intervenant\Service\IntervenantServiceAwareTrait;
    use MiseEnPaiementIntervenantStructureServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use TypeHeuresServiceAwareTrait;
    use MissionServiceAwareTrait;
    use ServiceAPayerServiceAwareTrait;
    use TblPaiementServiceAwareTrait;
    use Traits\WorkflowServiceAwareTrait;

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



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'mep';
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
     * Retourne les données du TBL des mises en paiement en fonction des critères de recherche transmis
     *
     * @param MiseEnPaiementRecherche $recherche
     *
     * @return array
     */
    public function getEtatPaiementCsv(MiseEnPaiementRecherche $recherche)
    {
        // initialisation
        $annee = $this->getServiceContext()->getAnnee();

        $data = [];

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

        $sql  = 'SELECT * FROM V_ETAT_PAIEMENT WHERE ' . implode(' AND ', $conditions) . ' ORDER BY INTERVENANT_NOM, CENTRE_COUT_CODE';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        // récupération des données
        while ($d = $stmt->fetch()) {
            $ds     = [
                'annee-libelle'            => (string)$annee,
                'etat'                     => $d['ETAT'],
                'structure-libelle'        => $d['STRUCTURE_LIBELLE'],
                'date-mise-en-paiement'    => empty($d['DATE_MISE_EN_PAIEMENT']) ? null : \DateTime::createFromFormat('Y-m-d', substr($d['DATE_MISE_EN_PAIEMENT'], 0, 10)),
                'periode-paiement-libelle' => $d['PERIODE_PAIEMENT_LIBELLE'],
                'intervenant-type'         => $d['INTERVENANT_TYPE'],
                'intervenant-code'         => $d['INTERVENANT_CODE'],
                'intervenant-nom'          => $d['INTERVENANT_NOM'],
                'intervenant-numero-insee' => $d['INTERVENANT_NUMERO_INSEE'],

                'centre-cout-code'            => $d['CENTRE_COUT_CODE'],
                'centre-cout-libelle'         => $d['CENTRE_COUT_LIBELLE'],
                'domaine-fonctionnel-code'    => $d['DOMAINE_FONCTIONNEL_CODE'],
                'domaine-fonctionnel-libelle' => $d['DOMAINE_FONCTIONNEL_LIBELLE'],

                'hetd'                => (float)$d['HETD'],
                'hetd-pourc'          => (float)$d['HETD_POURC'],
                'hetd-montant'        => (float)$d['HETD_MONTANT'],
                'rem-fc-d714'         => (float)$d['REM_FC_D714'],
                'exercice-aa'         => (float)$d['EXERCICE_AA'],
                'exercice-aa-montant' => (float)$d['EXERCICE_AA_MONTANT'],
                'exercice-ac'         => (float)$d['EXERCICE_AC'],
                'exercice-ac-montant' => (float)$d['EXERCICE_AC_MONTANT'],
            ];
            $data[] = $ds;
        }

        return $data;
    }



    /**
     * Retourne les données du TBL des services en fonction des critères de recherche transmis
     *
     * @param Recherche $recherche
     *
     * @return array
     */
    public function getTableauBord(?Structure $structure)
    {
        $annee = $this->getServiceContext()->getAnnee();
        $data  = [];

        $params = [
            'annee' => $annee->getId(),
        ];
        $sql    = 'SELECT * FROM v_export_dmep WHERE annee_id = :annee';

        if ($structure) {
            $params['structure'] = $structure->idsFilter();
            $sql                 .= ' AND structure_ids LIKE :structure';
        }

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        // récupération des données
        while ($d = $stmt->fetch()) {

            $ds = [
                'annee-libelle' => (string)$annee,

                'intervenant-code'               => $d['INTERVENANT_CODE'],
                'intervenant-code-rh'            => $d['CODE_RH'],
                'intervenant-nom'                => $d['INTERVENANT_NOM'],
                'intervenant-date-naissance'     => new \DateTime($d['INTERVENANT_DATE_NAISSANCE']),
                'intervenant-statut-libelle'     => $d['INTERVENANT_STATUT_LIBELLE'],
                'intervenant-type-code'          => $d['INTERVENANT_TYPE_CODE'],
                'intervenant-type-libelle'       => $d['INTERVENANT_TYPE_LIBELLE'],
                'intervenant-grade-code'         => $d['INTERVENANT_GRADE_CODE'],
                'intervenant-grade-libelle'      => $d['INTERVENANT_GRADE_LIBELLE'],
                'intervenant-discipline-code'    => $d['INTERVENANT_DISCIPLINE_CODE'],
                'intervenant-discipline-libelle' => $d['INTERVENANT_DISCIPLINE_LIBELLE'],
                'service-structure-aff-libelle'  => $d['SERVICE_STRUCTURE_AFF_LIBELLE'],

                'service-structure-ens-libelle' => $d['SERVICE_STRUCTURE_ENS_LIBELLE'],
                'groupe-type-formation-libelle' => $d['GROUPE_TYPE_FORMATION_LIBELLE'],
                'type-formation-libelle'        => $d['TYPE_FORMATION_LIBELLE'],
                'etape-niveau'                  => empty($d['ETAPE_NIVEAU']) ? null : (int)$d['ETAPE_NIVEAU'],
                'etape-code'                    => $d['ETAPE_CODE'],
                'etape-etablissement-libelle'   => $d['ETAPE_LIBELLE'] ? $d['ETAPE_LIBELLE'] : $d['ETABLISSEMENT_LIBELLE'],
                'element-code'                  => $d['ELEMENT_CODE'],
                'element-fonction-libelle'      => $d['ELEMENT_LIBELLE'] ? $d['ELEMENT_LIBELLE'] : $d['FONCTION_REFERENTIEL_LIBELLE'],
                'element-discipline-code'       => $d['ELEMENT_DISCIPLINE_CODE'],
                'element-discipline-libelle'    => $d['ELEMENT_DISCIPLINE_LIBELLE'],
                'element-taux-fi'               => (float)$d['ELEMENT_TAUX_FI'],
                'element-taux-fc'               => (float)$d['ELEMENT_TAUX_FC'],
                'element-taux-fa'               => (float)$d['ELEMENT_TAUX_FA'],
                'commentaires'                  => $d['COMMENTAIRES'],
                'element-source-libelle'        => $d['ELEMENT_SOURCE_LIBELLE'],

                'type-ressource-libelle'      => $d['TYPE_RESSOURCE_LIBELLE'],
                'centre-couts-code'           => $d['CENTRE_COUTS_CODE'],
                'centre-couts-libelle'        => $d['CENTRE_COUTS_LIBELLE'],
                'domaine-fonctionnel-code'    => $d['DOMAINE_FONCTIONNEL_CODE'],
                'domaine-fonctionnel-libelle' => $d['DOMAINE_FONCTIONNEL_LIBELLE'],
                'etat'                        => $d['ETAT'],
                'periode-libelle'             => $d['PERIODE_LIBELLE'],
                'date-mise-en-paiement'       => $d['DATE_MISE_EN_PAIEMENT'] ? new \DateTime($d['DATE_MISE_EN_PAIEMENT']) : null,
                'heures-fi'                   => (float)$d['HEURES_FI'],
                'heures-fa'                   => (float)$d['HEURES_FA'],
                'heures-fc'                   => (float)$d['HEURES_FC'],
                'heures-fc-majorees'          => (float)$d['HEURES_FC_MAJOREES'],
                'heures-referentiel'          => (float)$d['HEURES_REFERENTIEL'],
            ];

            $data[] = $ds;
        }

        return $data;
    }



    /**
     * Retourne le tableau de bord des liquidations.
     * Il retourne le nb d'heures demandées en paiement par type de ressource pour une structure donnée
     * et pour l'année courante
     *
     * Format de retour : [Structure.id][TypeRessource.id] = (float)Heures
     *                 ou [TypeRessource.id] = (float)Heures
     *
     * Si la structure n'est pas spécifiée alors on retourne le tableau pour chaque structure.
     *
     * @param Structure|null $structure
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTblLiquidation($structure = null)
    {
        if (empty($structure)) return $this->getTblLiquidationMS();
        if (is_array($structure)) return $this->getTblLiquidationMS($structure);

        if (!$structure instanceof Structure) {
            throw new RuntimeException('La structure fournie n\'est pas uns entité');
        }

        $annee = $this->getServiceContext()->getAnnee();

        $res = ['total' => 0];

        $sql = "
        SELECT
          tdl.type_ressource_id,
          tdl.heures
        FROM
          v_tbl_dmep_liquidation tdl
          JOIN structure str ON str.id = tdl.structure_id
        WHERE
          tdl.annee_id = :annee
          AND str.ids LIKE :structure";

        $params = [
            'annee'     => $annee->getId(),
            'structure' => $structure->idsFilter(),
        ];
        $stmt   = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
        while ($d = $stmt->fetch()) {
            $typeRessourceId = (int)$d['TYPE_RESSOURCE_ID'];
            $heures          = (float)$d['HEURES'];

            $res[$typeRessourceId] = $heures;
            $res['total']          += $heures;
        }

        return $res;
    }



    /**
     * @param array|Structure[] $structures
     *
     * @return array|int[]
     * @throws \Doctrine\DBAL\Exception
     */
    private function getTblLiquidationMS(array $structures = [])
    {
        $annee = $this->getServiceContext()->getAnnee();

        $res = ['total' => 0];

        $sql = "
        SELECT
          structure_id,
          type_ressource_id,
          heures
        FROM
          V_TBL_DMEP_LIQUIDATION
        WHERE
          annee_id = :annee
        ";

        $strFilters = [];
        foreach ($structures as $structure) {
            $strFilters[] = 'structure_ids LIKE \'' . $structure->idsFilter() . "'";
        }
        if (!empty($strFilters)) {
            $sql .= 'AND (' . implode(' OR ', $strFilters) . ')';
        }

        $params = [
            'annee' => $annee->getId(),
        ];

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
        while ($d = $stmt->fetchAssociative()) {
            $structureId     = (int)$d['STRUCTURE_ID'];
            $typeRessourceId = (int)$d['TYPE_RESSOURCE_ID'];
            $heures          = (float)$d['HEURES'];

            $res[$structureId][$typeRessourceId] = $heures;
            if (!isset($res[$structureId]['total'])) $res[$structureId]['total'] = 0;
            $res[$structureId]['total'] += $heures;
            $res['total']               += $heures;
        }

        return $res;
    }



    /**
     * Méthode permettant de faire l'ensemble des demandes de mise en paiement pour un intervenant (uniquement les heures avec des centres de cout pré-paramétrées)
     *
     * @param ?Intervenant $intervenant
     */


    public function demandesMisesEnPaiementIntervenant(?Intervenant $intervenant, ?Structure $structure): void
    {
        if ($intervenant instanceof Intervenant) {
            $heuresADemander = $this->getServiceTblPaiement()->getDemandesMisesEnPaiementByIntervenant($intervenant, $structure);
            //On fait les demandes de mise en paiement des heures à payer avec un centre de cout pré-paramétré
            foreach ($heuresADemander as $heures) {
                /**
                 * @var TblPaiement $heures
                 */
                //On récupére les données nécessaire à la demande de mis en paiement
                $data                   = [];
                $data['heures']         = $heures->getHeuresAPayerAA() + $heures->getHeuresAPayerAC();
                $data['centre-cout-id'] = ($heures->getCentreCout()) ? $heures->getCentreCout()->getId() : '';;
                $data['domaine-fonctionnel-id']                  = ($heures->getDomaineFonctionel()) ? $heures->getDomaineFonctionel()->getId() : '';
                $data['formule-resultat-service-id']             = ($heures->getFormuleResultatService()) ? $heures->getFormuleResultatService()->getId() : '';
                $data['formule-resultat-service-referentiel-id'] = ($heures->getFormuleResultatServiceReferentiel()) ? $heures->getFormuleResultatServiceReferentiel()->getId() : '';
                $data['mission-id']                              = ($heures->getMission()) ? $heures->getMission()->getId() : '';
                $data['type-heures-id']                          = ($heures->getTypeHeures()) ? $heures->getTypeHeures()->getId() : '';
                /* @var $miseEnPaiement MiseEnPaiement */
                //On enregistre la demande de mise en paiement
                $miseEnPaiement = $this->newEntity();
                $this->hydrateFromChangements($miseEnPaiement, $data);
                $this->save($miseEnPaiement);
                //on détruit les datas temporaires
                unset($data);
                //On recalcule le tableau de bord paiement de l'intervenant conserné
                $this->getServiceWorkflow()->calculerTableauxBord([
                                                                      'paiement',
                                                                  ], $intervenant);
            }
        }
    }



    private function hydrateFromChangements(MiseEnPaiement $object, $data)
    {
        if (isset($data['heures'])) {
            $object->setHeures((float)$data['heures']);
        }

        if (isset($data['centre-cout-id'])) {
            $object->setCentreCout($this->getServiceCentreCout()->get((int)$data['centre-cout-id']));
        }

        if (isset($data['domaine-fonctionnel-id'])) {
            $object->setDomaineFonctionnel($this->getServiceDomaineFonctionnel()->get((int)$data['domaine-fonctionnel-id']));
        }

        if (isset($data['formule-resultat-service-id'])) {
            $object->setFormuleResultatService($this->getServiceFormuleResultatService()->get((int)$data['formule-resultat-service-id']));
        }

        if (isset($data['formule-resultat-service-referentiel-id'])) {
            $object->setFormuleResultatServiceReferentiel($this->getServiceFormuleResultatServiceReferentiel()->get((int)$data['formule-resultat-service-referentiel-id']));
        }

        if (isset($data['mission-id'])) {
            $object->setMission($this->getServiceMission()->get((int)$data['mission-id']));
        }

        if (isset($data['type-heures-id'])) {
            $object->setTypeHeures($this->getServiceTypeHeures()->get((int)$data['type-heures-id']));
        }
    }



    /**
     * Sauvegarde tous les changements intervenus dans un ensemble de mises en paiement
     *
     * @param array $changements
     */
    public function saveChangements($changements)
    {
        foreach ($changements as $miseEnPaiementId => $data) {
            if (0 === strpos($miseEnPaiementId, 'new')) { // insert
                $miseEnPaiement = $this->newEntity();
                /* @var $miseEnPaiement MiseEnPaiement */
                $this->hydrateFromChangements($miseEnPaiement, $data);
                $this->save($miseEnPaiement);
            } else {
                $miseEnPaiement = $this->get($miseEnPaiementId);
                if (null == $data || 'removed' == $data) { // delete
                    $this->delete($miseEnPaiement);
                } else { // update
                    $this->hydrateFromChangements($miseEnPaiement, $data);
                    $this->save($miseEnPaiement);
                }
            }
        }
    }



    /**
     *
     * @param Structure                            $structure
     * @param \Intervenant\Entity\Db\Intervenant[] $intervenants
     * @param Periode                              $periodePaiement
     * @param \DateTime                            $dateMiseEnPaiement
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
     * @param ServiceAPayerInterface $sap
     *
     * @return $this
     */
    public function deleteHistorises(ServiceAPayerInterface $sap)
    {
        $sap->getMiseEnPaiement()->map(function (MiseEnPaiement $mep) {
            if (!$mep->estNonHistorise()) {
                $this->delete($mep, false);
            }
        });

        return $this;
    }

}