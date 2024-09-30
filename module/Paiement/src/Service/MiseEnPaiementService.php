<?php

namespace Paiement\Service;

use Application\Entity\Db\Periode;
use Application\Entity\Db\WfEtape;
use Application\Service\AbstractEntityService;
use Application\Service\Traits;
use Doctrine\ORM\QueryBuilder;
use Enseignement\Service\ServiceServiceAwareTrait;
use Formule\Entity\Db\FormuleResultatService;
use Formule\Entity\Db\FormuleResultatServiceReferentiel;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use OffreFormation\Service\Traits\TypeHeuresServiceAwareTrait;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\TblPaiement;
use Paiement\Entity\MiseEnPaiementRecherche;
use Referentiel\Service\ServiceReferentielServiceAwareTrait;
use RuntimeException;

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
    use DotationServiceAwareTrait;
    use TypeHeuresServiceAwareTrait;
    use MissionServiceAwareTrait;
    use ServiceReferentielServiceAwareTrait;
    use MissionServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use StructureServiceAwareTrait;
    use TblPaiementServiceAwareTrait;
    use Traits\WorkflowServiceAwareTrait;
    use Traits\ParametresServiceAwareTrait;

    const EXCEPTION_DMEP_CENTRE_COUT         = 1;
    const EXCEPTION_DMEP_DOMAINE_FONCTIONNEL = 2;
    const EXCEPTION_DMEP_INVALIDE            = 3;
    const EXCEPTION_DMEP_BUDGET              = 4;



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



    public function demandesMisesEnPaiementIntervenant(?Intervenant $intervenant): void
    {
        if ($intervenant instanceof Intervenant) {
            $heuresADemander = $this->getServiceTblPaiement()->getDemandesMisesEnPaiementByIntervenant($intervenant);
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
            $entity = $this->getEntityManager()->find(FormuleResultatService::class, (int)$data['formule-resultat-service-id']);
            $object->setFormuleResultatService($entity);
        }

        if (isset($data['formule-resultat-service-referentiel-id'])) {
            $entity = $this->getEntityManager()->find(FormuleResultatServiceReferentiel::class, (int)$data['formule-resultat-service-referentiel-id']);
            $object->setFormuleResultatServiceReferentiel($entity);
        }

        if (isset($data['mission-id'])) {
            $object->setMission($this->getServiceMission()->get((int)$data['mission-id']));
        }

        if (isset($data['type-heures-id'])) {
            $object->setTypeHeures($this->getServiceTypeHeures()->get((int)$data['type-heures-id']));
        }
    }



    public function ajouterDemandeMiseEnPaiement(Intervenant $intervenant, array $datas): bool|MiseEnPaiement
    {

        $data['heures']                 = (array_key_exists('heures', $datas)) ? $datas['heures'] : '';
        $data['type-heures-id']         = (array_key_exists('typeHeuresId', $datas)) ? $datas['typeHeuresId'] : '';
        $data['centre-cout-id']         = (array_key_exists('centreCoutId', $datas)) ? $datas['centreCoutId'] : '';
        $data['service-id']             = (array_key_exists('serviceId', $datas)) ? $datas['serviceId'] : '';
        $data['service-referentiel-id'] = (array_key_exists('serviceReferentielId', $datas)) ? $datas['serviceReferentielId'] : '';
        $data['domaine-fonctionnel-id'] = (array_key_exists('domaineFonctionnelId', $datas)) ? $datas['domaineFonctionnelId'] : '';
        $data['mission-id']             = (array_key_exists('missionId', $datas)) ? $datas['missionId'] : '';


        /* @var $miseEnPaiement MiseEnPaiement */
        //On enregistre la demande de mise en paiement
        $miseEnPaiement = $this->newEntity();
        $this->hydrateFromChangements($miseEnPaiement, $data);
        $this->save($miseEnPaiement);


        return $miseEnPaiement;
    }



    public function supprimerDemandeMiseEnPaiement($idMep): bool
    {
        /**
         * @var MiseEnPaiement $mep
         */

        $mep = $this->getEntityManager()->getRepository(MiseEnPaiement::class)->find($idMep);
        if (!empty($mep)) {
            if (!$mep->getPeriodePaiement()) {
                $this->delete($mep);
            } else {
                throw new \Exception("Vous ne pouvez pas supprimer cette demande de mise en paiement, les heures on déjà été payé");
            }

            return true;
        } else {
            throw new \Exception("Demande de mise en paiement non trouvée");
        }
    }



    /**
     *
     * @param Intervenant $intervenant
     * @param ?Structure  $structure
     *
     */
    public function getDemandeMiseEnPaiementResume(Intervenant $intervenant, ?Structure $structure = null)
    {
        //Centres de cout de la composante d'affectation de l'intervenant
        $centresCoutsPaiementAffectation = [];
        //Liste des demandes de mise en paiement
        $dmep = [];
        //Récupération du paramétrage des centres de cout pour les paiement
        $parametreCentreCout = $this->getServiceParametres()->get('centres_couts_paye');

        $sql = "
        
        SELECT
            tp.intervenant_id 				    intervenant_id,
            tp.structure_id                     structure_id,
            MAX(s.code)                         structure_code,
            MAX(s.libelle_long)   			    structure_libelle,
            MAX(s.libelle_court)   			    structure_libelle_court,
            CASE
                WHEN MAX(tp.service_id) IS NOT NULL THEN 'enseignement'
                WHEN MAX(tp.service_referentiel_id) IS NOT NULL THEN 'referentiel'
                ELSE 'mission'
            END 							    typage,
            MAX(e.id)   					    etape_id,
            e.code 							    etape_code,
            MAX(e.libelle) 					    etape_libelle,
            MAX(ep.id)   					    element_id,
            ep.code 						    element_code,
            MAX(ep.libelle) 				    element_libelle,
            MAX(fr.id)      				    fonction_id,
            fr.code    						    fonction_code,
            MAX(fr.libelle_long)  			    fonction_libelle,
            MAX(th.id) 				  		    type_heure_id,	 
            CASE WHEN th.code = 'fc_majorees' 
                THEN 'fcMajorees' 
                ELSE th.code END   			    type_heure_code,
            MAX(th.libelle_long)  			    type_heure_libelle,
            COALESCE(tp.mise_en_paiement_id,0)  mep_id,
            MAX(cc.id)     					    centre_cout_id,
            MAX(cc.code)   					    centre_cout_code,
            MAX(cc.libelle)   				    centre_cout_libelle,
            MAX(p.code)						    periode_code,
            MAX(p.libelle_long)				    periode_libelle,
            SUM(tp.heures_a_payer_aa + 
            tp.heures_a_payer_ac)   		    heures_a_payer,
            SUM(tp.heures_demandees_aa + 
            tp.heures_demandees_ac) 		    heures_demandees,
            SUM(tp.heures_payees_aa + 
            tp.heures_payees_ac) 		        heures_payees,
            MAX(tp.domaine_fonctionnel_id)      domaine_fonctionnel_id,
            MAX(df.libelle)                     domaine_fonctionnel_libelle,
            MAX(df.source_code)                 domaine_fonctionnel_code,
            MAX(tp.formule_res_service_id)      formule_res_service_id,
            MAX(tp.formule_res_service_ref_id)  formule_res_service_ref_id,
            MAX(tp.service_id)                  service_id,
            MAX(tp.service_referentiel_id)      service_referentiel_id,
            MAX(tp.mission_id)                  mission_id,
            MAX(tm.libelle) || 
            ' / ' || 
            MAX(m.libelle_mission)              mission_libelle,
            MAX(mep.date_mise_en_paiement)      date_paiement,
            MAX(mep.histo_creation)             date_demande
            
            
        FROM
            tbl_paiement tp
        LEFT JOIN structure s ON s.id = tp.structure_id 
        LEFT JOIN service s ON	s.id = tp.service_id
        LEFT JOIN service_referentiel sr ON	sr.id = tp.service_referentiel_id
        LEFT JOIN element_pedagogique ep ON	ep.id = s.element_pedagogique_id
        LEFT JOIN etape e ON e.id = ep.etape_id
        LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id 
        LEFT JOIN type_heures th ON th.id = tp.type_heures_id 
        LEFT JOIN mise_en_paiement mep ON mep.id = tp.mise_en_paiement_id AND mep.histo_destruction IS NULL
        LEFT JOIN centre_cout cc ON cc.id = tp.centre_cout_id 
        LEFT JOIN domaine_fonctionnel df ON df.id = tp.domaine_fonctionnel_id
        LEFT JOIN periode p ON p.id = tp.periode_paiement_id
        LEFT JOIN mission m ON m.id = tp.mission_id
        LEFT JOIN type_mission tm ON tm.id = m.type_mission_id
        WHERE
            tp.intervenant_id = :intervenant ";

        //Si on ne veut que les heures à payer d'une structure donnée.
        if (!empty($structure)) {
            $sql .= " AND tp.structure_id = :structure";
        }

        $sql .= "
        GROUP BY
            tp.intervenant_id ,
            tp.structure_id,
            e.code,
            ep.code,
            fr.code,
            th.code,
            tp.mise_en_paiement_id  
        ORDER BY 
            tp.structure_id,
            MAX(e.libelle) ASC,
            MAX(ep.libelle) ASC,
            MAX(th.code) ASC,
            MAX(fr.libelle_long) ASC,
            tp.mise_en_paiement_id ASC

        ";

        $params['intervenant'] = $intervenant->getId();

        if (!empty($structure)) {
            $params['structure'] = $structure->getId();
        }

        $dmeps = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);


        //On récupere la liste des domaines fonctionnels
        $listeDomainesFonctionnels = [];
        $domainesFonctionels       = $this->getServiceDomaineFonctionnel()->getList();
        foreach ($domainesFonctionels as $d) {
            $listeDomainesFonctionnels[] = [
                'domaineFonctionnelId'      => $d->getId(),
                'domaineFonctionnelLibelle' => $d->getLibelle(),
                'domaineFonctionnelCode'    => $d->getSourceCode(),
            ];
        }


        foreach ($dmeps as $value) {
            //On traite ici les heures d'enseignement
            if ($value['TYPAGE'] == "enseignement") {
                //Partie enseignements
                $dmep[$value['STRUCTURE_CODE']]['code']                                                                                                                     = $value['STRUCTURE_CODE'];
                $dmep[$value['STRUCTURE_CODE']]['id']                                                                                                                       = $value['STRUCTURE_ID'];
                $dmep[$value['STRUCTURE_CODE']]['libelle']                                                                                                                  = $value['STRUCTURE_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['libelleCourt']                                                                                                             = $value['STRUCTURE_LIBELLE_COURT'];
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['libelle']                                                                                  = $value['ETAPE_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['libelle']                                         = $value['ELEMENT_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['libelle'] = $value['TYPE_HEURE_LIBELLE'];
                if (!array_key_exists('heures', $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']])) {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures'] = [];
                }
                //Heure déjà mise en paiement
                if (!empty($value['MEP_ID'])) {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures']['mep_id_' . $value['MEP_ID']] = [
                        'mepId'                  => $value['MEP_ID'],
                        'typeHeureId'            => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'          => $value['TYPE_HEURE_CODE'],
                        'formuleResServiceId'    => $value['FORMULE_RES_SERVICE_ID'],
                        'formuleResServiceRefId' => $value['FORMULE_RES_SERVICE_REF_ID'],
                        'serviceId'              => $value['SERVICE_ID'],
                        'serviceReferentielId'   => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
                        'periodeLibelle'         => $value['PERIODE_LIBELLE'],
                        'periodeCode'            => $value['PERIODE_CODE'],
                        'datePaiement'           => $value['DATE_PAIEMENT'],
                        'dateDemande'            => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'     => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'             => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                } else {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures']['a_demander'] = [
                        'mepId'                  => '',
                        'typeHeureId'            => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'          => $value['TYPE_HEURE_CODE'],
                        'formuleResServiceId'    => $value['FORMULE_RES_SERVICE_ID'],
                        'formuleResServiceRefId' => $value['FORMULE_RES_SERVICE_REF_ID'],
                        'serviceId'              => $value['SERVICE_ID'],
                        'serviceReferentielId'   => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
                        'periodeLibelle'         => $value['PERIODE_LIBELLE'],
                        'periodeCode'            => $value['PERIODE_CODE'],
                        'datePaiement'           => $value['DATE_PAIEMENT'],
                        'dateDemande'            => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'     => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'             => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                }
            } elseif ($value['TYPAGE'] == "referentiel") {
                //Partie référentiel
                $dmep[$value['STRUCTURE_CODE']]['code']                                                      = $value['STRUCTURE_CODE'];
                $dmep[$value['STRUCTURE_CODE']]['id']                                                        = $value['STRUCTURE_ID'];
                $dmep[$value['STRUCTURE_CODE']]['libelle']                                                   = $value['STRUCTURE_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['libelleCourt']                                              = $value['STRUCTURE_LIBELLE_COURT'];
                $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$value['FONCTION_CODE']]['libelle'] = $value['FONCTION_LIBELLE'];
                if (!array_key_exists('heures', $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$value['FONCTION_CODE']])) {
                    $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$value['FONCTION_CODE']]['heures'] = [];
                }
                if (!empty($value['MEP_ID'])) {
                    $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$value['FONCTION_CODE']]['heures']['mep_id_' . $value['MEP_ID']] = [
                        'mepId'                  => $value['MEP_ID'],
                        'typeHeureId'            => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'          => $value['TYPE_HEURE_CODE'],
                        'formuleResServiceId'    => $value['FORMULE_RES_SERVICE_ID'],
                        'formuleResServiceRefId' => $value['FORMULE_RES_SERVICE_REF_ID'],
                        'serviceId'              => $value['SERVICE_ID'],
                        'serviceReferentielId'   => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'periodeLibelle'         => $value['PERIODE_LIBELLE'],
                        'periodeCode'            => $value['PERIODE_CODE'],
                        'datePaiement'           => $value['DATE_PAIEMENT'],
                        'dateDemande'            => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'     => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'             => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                } else {
                    $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$value['FONCTION_CODE']]['heures']['a_demander'] = [
                        'mepId'                  => '',
                        'typeHeureId'            => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'          => $value['TYPE_HEURE_CODE'],
                        'formuleResServiceId'    => $value['FORMULE_RES_SERVICE_ID'],
                        'formuleResServiceRefId' => $value['FORMULE_RES_SERVICE_REF_ID'],
                        'serviceId'              => $value['SERVICE_ID'],
                        'serviceReferentielId'   => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'periodeLibelle'         => $value['PERIODE_LIBELLE'],
                        'periodeCode'            => $value['PERIODE_CODE'],
                        'datePaiement'           => $value['DATE_PAIEMENT'],
                        'dateDemande'            => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'     => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'             => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                }
            } elseif ($value['TYPAGE'] == "mission") {
                //Partie mission
                $dmep[$value['STRUCTURE_CODE']]['code']                                        = $value['STRUCTURE_CODE'];
                $dmep[$value['STRUCTURE_CODE']]['id']                                          = $value['STRUCTURE_ID'];
                $dmep[$value['STRUCTURE_CODE']]['libelle']                                     = $value['STRUCTURE_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['libelleCourt']                                = $value['STRUCTURE_LIBELLE_COURT'];
                $dmep[$value['STRUCTURE_CODE']]['missions'][$value['MISSION_ID']]['libelle']   = $value['MISSION_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['missions'][$value['MISSION_ID']]['missionId'] = $value['MISSION_ID'];
                if (!array_key_exists('heures', $dmep[$value['STRUCTURE_CODE']]['missions'][$value['MISSION_ID']])) {
                    $dmep[$value['STRUCTURE_CODE']]['missions'][$value['MISSION_ID']]['heures'] = [];
                }
                if (!empty($value['MEP_ID'])) {
                    $dmep[$value['STRUCTURE_CODE']]['missions'][$value['MISSION_ID']]['heures']['mep_id_' . $value['MEP_ID']] = [
                        'mepId'                  => $value['MEP_ID'],
                        'typeHeureId'            => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'          => $value['TYPE_HEURE_CODE'],
                        'formuleResServiceId'    => $value['FORMULE_RES_SERVICE_ID'],
                        'formuleResServiceRefId' => $value['FORMULE_RES_SERVICE_REF_ID'],
                        'serviceId'              => $value['SERVICE_ID'],
                        'serviceReferentielId'   => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'periodeLibelle'         => $value['PERIODE_LIBELLE'],
                        'periodeCode'            => $value['PERIODE_CODE'],
                        'datePaiement'           => $value['DATE_PAIEMENT'],
                        'dateDemande'            => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'     => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'             => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                } else {
                    $dmep[$value['STRUCTURE_CODE']]['missions'][$value['MISSION_ID']]['heures']['a_demander'] = [
                        'mepId'                  => '',
                        'typeHeureId'            => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'          => $value['TYPE_HEURE_CODE'],
                        'formuleResServiceId'    => $value['FORMULE_RES_SERVICE_ID'],
                        'formuleResServiceRefId' => $value['FORMULE_RES_SERVICE_REF_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'periodeLibelle'         => $value['PERIODE_LIBELLE'],
                        'periodeCode'            => $value['PERIODE_CODE'],
                        'datePaiement'           => $value['DATE_PAIEMENT'],
                        'dateDemande'            => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'     => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'             => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                }
            }


            //On alimente les centres couts disponibles pour ces demandes de mise en paiement
            //Si le paramétrage est affectation, on va chercher une fois pour toutes les centres de couts de paiement de la structure d'affectation de l'intervenant
            if ($parametreCentreCout == 'affectation' && empty($centresCoutsPaiementAffectation)) {
                $structure                       = $this->getServiceStructure()->get($intervenant->getStructure()->getId());
                $centresCoutsPaiementAffectation = $this->getServiceCentreCout()->getCentresCoutsMiseEnPaiement($structure);
            }

            if (!array_key_exists('centreCoutPaiement', $dmep[$value['STRUCTURE_CODE']])) {
                $dmep[$value['STRUCTURE_CODE']]['centreCoutPaiement'] = [];
                if ($parametreCentreCout == 'enseignement') {
                    $structure    = $this->getServiceStructure()->get($value['STRUCTURE_ID']);
                    $centresCouts = $this->getServiceCentreCout()->getCentresCoutsMiseEnPaiement($structure);
                } else {
                    $centresCouts = $centresCoutsPaiementAffectation;
                }
                $listeCentresCouts = [];
                foreach ($centresCouts as $centreCout) {
                    if (!empty($centreCout['CODE_PARENT'])) {
                        if (!array_key_exists($centreCout['CODE_PARENT'] . ' - ' . $centreCout['LIBELLE_PARENT'], $listeCentresCouts)) {
                            $listeCentresCouts[$centreCout['CODE_PARENT'] . ' - ' . $centreCout['LIBELLE_PARENT']] = [];
                        }
                        $listeCentresCouts[$centreCout['CODE_PARENT'] . ' - ' . $centreCout['LIBELLE_PARENT']][] = [
                            'centreCoutId'      => $centreCout['CENTRE_COUT_ID'],
                            'centreCoutLibelle' => $centreCout['LIBELLE'],
                            'centreCoutCode'    => $centreCout['CODE'],
                            'fi'                => $centreCout['FI'],
                            'fa'                => $centreCout['FA'],
                            'fc'                => $centreCout['FC'],
                            'enseignement'      => $centreCout['ENSEIGNEMENT'],
                            'referentiel'       => $centreCout['REFERENTIEL'],
                            'fcMajorees'        => $centreCout['FC_MAJOREES'],
                            'mission'           => $centreCout['MISSION'],
                            'paieEtat'          => $centreCout['PAIE_ETAT'],
                            'ressourcesPropres' => $centreCout['RESSOURCES_PROPRES'],

                        ];
                    } else {
                        $listeCentresCouts['AUTRES'][] = [
                            'centreCoutId'      => $centreCout['CENTRE_COUT_ID'],
                            'centreCoutLibelle' => $centreCout['LIBELLE'],
                            'centreCoutCode'    => $centreCout['CODE'],
                            'fi'                => $centreCout['FI'],
                            'fa'                => $centreCout['FA'],
                            'fc'                => $centreCout['FC'],
                            'enseignement'      => $centreCout['ENSEIGNEMENT'],
                            'referentiel'       => $centreCout['REFERENTIEL'],
                            'fcMajorees'        => $centreCout['FC_MAJOREES'],
                            'mission'           => $centreCout['MISSION'],
                            'paieEtat'          => $centreCout['PAIE_ETAT'],
                            'ressourcesPropres' => $centreCout['RESSOURCES_PROPRES'],
                        ];
                    }

                    $dmep[$value['STRUCTURE_CODE']]['centreCoutPaiement'] = $listeCentresCouts;
                }
            }

            if (!array_key_exists('domaineFonctionnelPaiement', $dmep[$value['STRUCTURE_CODE']])) {
                $dmep[$value['STRUCTURE_CODE']]['domaineFonctionnelPaiement'] = $listeDomainesFonctionnels;
            }
            //On va chercher le budget de la composante (dotation et liquidation)
            $structure                                = $this->getServiceStructure()->get($value['STRUCTURE_ID']);
            $budget                                   = $this->getBudgetPaiement($structure);
            $dmep[$value['STRUCTURE_CODE']]['budget'] = $budget;
        }

        return $dmep;
    }



    public function getBudgetPaiement(Structure $structure): array
    {
        $budget = [
            'dotation'    => [
                'paieEtat'        => 0,
                'ressourcePropre' => 0,
                'total'           => 0,
            ],
            'liquidation' => [
                'paieEtat'        => 0,
                'ressourcePropre' => 0,
                'total'           => 0,
            ],

        ];
        if ($structure instanceof Structure) {
            $dotation    = $this->getServiceDotation()->getTableauBord([$structure->getId()]);
            $liquidation = $this->getTblLiquidation($structure);
            foreach ($dotation as $key => $value) {
                if ($key == $structure->getId()) {
                    $budget['dotation']['paieEtat']        = (key_exists(1, $value)) ? $value['1'] : 0;
                    $budget['dotation']['ressourcePropre'] = (key_exists(2, $value)) ? $value['2'] : 0;
                    $budget['dotation']['total']           = $value['total'];
                    break;
                }
            }
            //liquidation
            $budget['liquidation']['paieEtat']        = (key_exists('1', $liquidation)) ? $liquidation['1'] : 0;
            $budget['liquidation']['ressourcePropre'] = (key_exists('2', $liquidation)) ? $liquidation['2'] : 0;
            $budget['liquidation']['total']           = $liquidation['total'];
        }

        return $budget;
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



    public function verifierBudgetDemandeMiseEnPaiement(array $demandes): array
    {
        $demandesApprouvees                      = [];
        $totalHeuresDemandees['ressourcePropre'] = 0;
        $totalHeuresDemandees['paieEtat']        = 0;

        //1 - On récupére le budget de la structure pour laquelle on a des heures à demander
        foreach ($demandes as $demande) {
            $structure = $this->getEntityManager()->getRepository(Structure::class)->find($demande['structureId']);
            $structure = $this->getServiceStructure()->get($demande['structureId']);
            if ($structure instanceof Structure) {

                $budget = $this->getBudgetPaiement($structure);
                //2 - On récupére le centre de cout que nous souhaitons utiliser pour la demande de mise en paiement

                $centreCout = $this->getEntityManager()->getRepository(CentreCout::class)->find($demande['centreCoutId']);
                $centreCout = $this->getServiceCentreCout()->get($demande['centreCoutId']);
                if ($centreCout instanceof CentreCout) {
                    //3 - on vérifier si il y a du budget dans le type de ressource auquel est rattaché ce centre de cout
                    if ($centreCout->getTypeRessource()->getCode() == 'ressources-propres') {
                        if ($budget['dotation']['ressourcePropre'] > 0) {

                            //4 - on regarde si il y a encore assez de budget pour demander les heures en paiement
                            $total = $budget['liquidation']['ressourcePropre'] + $demande['heures'] + $totalHeuresDemandees['ressourcePropre'];
                            if ($total <= $budget['dotation']['ressourcePropre']) {
                                $totalHeuresDemandees['ressourcePropre'] += $demande['heures'];
                            } else {
                                continue;
                            }
                        }
                        $demandesApprouvees[] = $demande;
                    }
                    if ($centreCout->getTypeRessource()->getCode() == 'paie-etat') {
                        //Si la dotation est supérieur à 0, alors on vérifie si il reste du budget disponible
                        if ($budget['dotation']['paieEtat'] > 0) {
                            //4bis - on regarde si il y a encore assez de budget pour demander les heures en paiement
                            $total = $budget['liquidation']['paieEtat'] + $demande['heures'] + $totalHeuresDemandees['paieEtat'];
                            if ($total <= $budget['dotation']['paieEtat']) {
                                $totalHeuresDemandees['paieEtat'] += $demande['heures'];
                            } else {
                                continue;
                            }
                        }
                        $demandesApprouvees[] = $demande;
                    }
                } else {
                    $demandesApprouvees[] = $demande;
                }
            }
        }

        return $demandesApprouvees;
    }



    public function verifierValiditeDemandeMiseEnPaiement(Intervenant $intervenant, $data)
    {
        $sql = "
            SELECT
                SUM(tp.heures_a_payer_aa + tp.heures_a_payer_ac)        total_heures_a_payer,
                SUM(tp.heures_demandees_aa + tp.heures_demandees_ac)    total_heures_demandees,
                SUM(tp.heures_payees_aa + tp.heures_payees_ac)          total_heures_payees,
                service_id                                              service_id,
                service_referentiel_id                                  service_referentiel_id,
                mission_id                                              mission_id
            FROM
                tbl_paiement tp
            WHERE
                tp.intervenant_id = :intervenant
            GROUP BY 
                service_id,
                service_referentiel_id,
                mission_id
        ";

        $dmeps                = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, [
            'intervenant' => $intervenant->getId(),
        ]);
        $totalHeuresAPayer    = 0;
        $totalHeuresDemandees = 0;
        $totalHeuresPayees    = 0;
        $heuresDemandees      = $data['heures'];
        $serviceId            = $data['serviceId'];
        $serviceReferentielId = $data['serviceReferentielId'];
        $missionId            = $data['missionId'];
        foreach ($dmeps as $dmep) {
            $totalHeuresDemandees += $dmep['TOTAL_HEURES_DEMANDEES'];
            $totalHeuresPayees    += $dmep['TOTAL_HEURES_PAYEES'];
            $totalHeuresAPayer    += $dmep['TOTAL_HEURES_A_PAYER'];
            if ($serviceReferentielId === $dmep['SERVICE_REFERENTIEL_ID'] || $serviceId === $dmep['SERVICE_ID'] || $missionId === $dmep['MISSION_ID']) {
                $soldeHeures = ($dmep['TOTAL_HEURES_A_PAYER'] - $dmep['TOTAL_HEURES_DEMANDEES']);
                if ($heuresDemandees > $soldeHeures) {
                    if ($soldeHeures >= 0) {
                        throw new \Exception('Demande de mise en paiement impossible, vous demandez ' . $heuresDemandees . ' hetd(s) alors que vous pouvez demander maximum ' . ($dmep['TOTAL_HEURES_A_PAYER'] - $dmep['TOTAL_HEURES_DEMANDEES']) . ' hetd(s)', self::EXCEPTION_DMEP_INVALIDE);
                    } else {
                        throw new \Exception('Demande de mise en paiement impossible, vous demandez ' . $heuresDemandees . ' hetd(s) alors que vous avez déjà payé la totalité des heures pour cet enseignement (' . $dmep['TOTAL_HEURES_A_PAYER'] . ' hetd(s))', self::EXCEPTION_DMEP_INVALIDE);
                    }
                }
            }
        }
        //On  vérifie qu'il y a bien un centre de cout
        if (empty($data['centreCoutId'])) {
            throw new \Exception('Vous devez renseigner un centre de cout pour demander ce paiement', self::EXCEPTION_DMEP_CENTRE_COUT);
        }
        //On vérifie qu'il y a bien un domaine fonctionnel dans le cadre du référentiel et des missions
        if (!empty($data['missionId']) || !empty($data['formuleResServiceRefId'])) {
            if (empty($data['domaineFonctionnelId'])) {
                throw new \Exception('Vous devez renseigner un domaine fonctionnel pour demander ce paiement', self::EXCEPTION_DMEP_DOMAINE_FONCTIONNEL);
            }
        }
        //On vérifie en dernier si l'ensemble des heures déjà payé ne dépasse pas le nombre d'heures réalisées tout service confondu.
        if (($totalHeuresAPayer - $totalHeuresDemandees) < $heuresDemandees) {
            throw new \Exception('Demande de mise en paiement impossible, la somme des heures déjà demandée en paiement pour tous les services confondus ne permet plus de demander en paiement les ' . $heuresDemandees . ' hetd(s)', self::EXCEPTION_DMEP_INVALIDE);
        }


        return true;
    }



    public function getListByStructure(Structure $structure)
    {
        $dql = "
        SELECT
            tp
        FROM
            " . TblPaiement::class . " tp
        JOIN tp.intervenant i 
        WHERE
            tp. structure = :structure
        AND tp.annee = :annee
        ORDER BY i.nomUsuel ASC
        ";


        /** @var TblPaiement[] $meps */
        $annee = $this->getServiceContext()->getAnnee();

        $dmeps                   = $this->getEntityManager()->createQuery($dql)->setParameters(['structure' => $structure, 'annee' => $annee])->getResult();
        $dmep                    = [];
        $intervenantsTotalHeures = [];
        foreach ($dmeps as $value) {
            /**
             * @var TblPaiement $value
             */
            $intervenant = $value->getIntervenant();
            if (!array_key_exists($intervenant->getId(), $intervenantsTotalHeures)) {
                $intervenantsTotalHeures[$intervenant->getId()]['heuresDemandees'] = 0;
                $intervenantsTotalHeures[$intervenant->getId()]['heuresAPayer']    = 0;
            }
            $intervenantsTotalHeures[$intervenant->getId()] ['heuresDemandees'] += $value->getHeuresDemandees();
            $intervenantsTotalHeures[$intervenant->getId()] ['heuresAPayer']    += $value->getHeuresAPayer();


            if (empty($value->getMiseEnPaiement())) {
                $workflowEtape = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_DEMANDE_MEP, $intervenant, $value->getStructure());
                //Si l'étape de demande de mise en paiement n'est pas atteignable alors on ne le propose pas
                if (!$workflowEtape || !$workflowEtape->isAtteignable()) {
                    continue;
                }
                //On ne prend pas le référentiel dans les demandes de mise en paiement en lot
                if (!$value->getFormuleResultatServiceReferentiel()) {
                    if (!array_key_exists($intervenant->getId(), $dmep)) {

                        $dmep[$intervenant->getId()]['datasIntervenant'] = [
                            'id'              => $intervenant->getId(),
                            'code'            => $intervenant->getCode(),
                            'nom_usuel'       => $intervenant->getNomUsuel(),
                            'prenom'          => $intervenant->getPrenom(),
                            'structure'       => ($intervenant->getStructure()) ? $intervenant->getStructure()->getLibelleCourt() : '',
                            'statut'          => $intervenant->getStatut()->getLibelle(),
                            'typeIntervenant' => $intervenant->getStatut()->getTypeIntervenant()->getLibelle(),
                        ];
                    }
                    //On prend uniquement les heures ou hetd qui ne sont pas du référentiel

                    $dmep[$intervenant->getId()]['heures'][] = [
                        'heuresAPayer'       => $value->getHeuresAPayerAC() + $value->getHeuresAPayerAA(),
                        'missionId'          => ($value->getMission()) ? $value->getMission()->getId() : '',
                        'serviceId'          => ($value->getFormuleResultatService()) ? $value->getFormuleResultatService()->getService()->getId() : '',
                        'serviceRefId'       => ($value->getFormuleResultatServiceReferentiel()) ? $value->getFormuleResultatServiceReferentiel()->getServiceReferentiel()->getId() : '',
                        'centreCout'         => ['libelle'              => ($value->getCentreCout()) ? $value->getCentreCout()->getLibelle() : '',
                                                 'code'                 => ($value->getCentreCout()) ? $value->getCentreCout()->getCode() : '',
                                                 'typeRessourceCode'    => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getCode() : '',
                                                 'typeRessourceLibelle' => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getLibelle() : '',
                        ],
                        'domaineFonctionnel' => ['libelle' => ($value->getDomaineFonctionel()) ? $value->getDomaineFonctionel()->getLibelle() : '',
                                                 'code'    => ($value->getDomaineFonctionel()) ? $value->getDomaineFonctionel()->getSourceCode() : '',
                        ],
                    ];
                }
            }
        }

        //On enlève les intervenants dont le nombre d'heures demandées en paiement est supérieur au nombre d'heure à payer
        foreach ($intervenantsTotalHeures as $intervenantId => $heures) {
            if ($heures['heuresDemandees'] > $heures['heuresAPayer']) {
                if (array_key_exists($intervenantId, $dmep)) {
                    unset($dmep[$intervenantId]);
                }
            }
        }

        return $dmep;
    }



    /**
     *
     * @param Structure                            $structure
     * @param \Application\Entity\Db\Intervenant[] $intervenants
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

}