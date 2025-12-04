<?php

namespace Paiement\Service;


use Application\Entity\Db\WfEtape;
use Application\Service\AbstractService;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use OffreFormation\Service\TypeHeuresServiceAwareTrait;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\Db\TblPaiement;
use Referentiel\Entity\Db\ServiceReferentiel;

/**
 * Description of DemandesService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DemandesService extends AbstractService
{

    use MiseEnPaiementIntervenantStructureServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use DotationServiceAwareTrait;
    use TypeHeuresServiceAwareTrait;
    use MissionServiceAwareTrait;
    use MissionServiceAwareTrait;
    use StructureServiceAwareTrait;
    use TblPaiementServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use MiseEnPaiementServiceAwareTrait;
    use BudgetServiceAwareTrait;

    const EXCEPTION_DMEP_CENTRE_COUT         = 1;
    const EXCEPTION_DMEP_DOMAINE_FONCTIONNEL = 2;
    const EXCEPTION_DMEP_INVALIDE            = 3;
    const EXCEPTION_DMEP_BUDGET              = 4;



    public function demandesMisesEnPaiementIntervenant(?Intervenant $intervenant): void
    {
        if ($intervenant instanceof Intervenant) {
            $heuresADemander = $this->getServiceTblPaiement()->getDemandesMisesEnPaiementByIntervenant($intervenant);
            //On fait les demandes de mise en paiement des heures à payer avec un centre de cout pré-paramétré
            foreach ($heuresADemander as $heures) {
                //On récupère les données nécessaires à la demande de mis en paiement
                $data                   = [];
                $data['heures']         = $heures->getHeuresAPayerAA() + $heures->getHeuresAPayerAC();
                $data['centre-cout-id'] = ($heures->getCentreCout()) ? $heures->getCentreCout()->getId() : '';;
                $data['domaine-fonctionnel-id'] = ($heures->getDomaineFonctionnel()) ? $heures->getDomaineFonctionnel()->getId() : '';
                $data['service-id']             = ($heures->getService()) ? $heures->getService()->getId() : '';
                $data['service-referentiel-id'] = ($heures->getServiceReferentiel()) ? $heures->getServiceReferentiel()->getId() : '';
                $data['mission-id']             = ($heures->getMission()) ? $heures->getMission()->getId() : '';
                $data['type-heures-id']         = ($heures->getTypeHeures()) ? $heures->getTypeHeures()->getId() : '';
                /* @va $miseEnPaiement MiseEnPaiement */
                //On enregistre la demande de mise en paiement
                $miseEnPaiement = $this->getServiceMiseEnPaiement()->newEntity();
                $this->hydrateFromChangements($miseEnPaiement, $data);
                $this->getServiceMiseEnPaiement()->save($miseEnPaiement);
                //on détruit les datas temporaires
                unset($data);
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

        if (isset($data['service-id'])) {
            $entity = $this->getEntityManager()->find(Service::class, (int)$data['service-id']);
            $object->setService($entity);
        }

        if (isset($data['service-referentiel-id'])) {
            $entity = $this->getEntityManager()->find(ServiceReferentiel::class, (int)$data['service-referentiel-id']);
            $object->setServiceReferentiel($entity);
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
        $miseEnPaiement = $this->getServiceMiseEnPaiement()->newEntity();
        $this->hydrateFromChangements($miseEnPaiement, $data);
        $this->getServiceMiseEnPaiement()->save($miseEnPaiement);


        return $miseEnPaiement;
    }



    public function supprimerDemandeMiseEnPaiement(int $idMep): bool
    {
        /**
         * @var MiseEnPaiement $mep
         */

        $mep = $this->getServiceMiseEnPaiement()->get($idMep);
        if (!empty($mep)) {
            if (!$mep->getPeriodePaiement()) {
                $this->getServiceMiseEnPaiement()->delete($mep);
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
        //Récupération du paramétrage des centres de cout pour les paiements
        $intervenantStructure = $intervenant->getStructure();
        $parametreCentreCout = $this->getServiceParametres()->get('centres_couts_paye');
        if ($intervenant->getStatut()->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_EXTERIEUR ||
            $intervenant->getStatut()->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_ETUDIANT) {
            $parametreCentreCout = 'enseignement';
        }


        $sql = "
        
        SELECT
            tp.intervenant_id 				    intervenant_id,
            tp.structure_id                     structure_id,
            COALESCE(MAX(se.code), 
                     MAX(ssr.code),
                     MAX(sm.code),
                     MAX(si.code))              structure_code,
            COALESCE(MAX(se.libelle_long), 
                     MAX(ssr.libelle_long),
                     MAX(sm.libelle_long),
                     MAX(si.libelle_long))      structure_libelle,
            COALESCE(MAX(se.libelle_court), 
                     MAX(ssr.libelle_court),
                     MAX(sm.libelle_court),
                     MAX(si.libelle_court))   	structure_libelle_court,
            CASE
                WHEN MAX(tp.service_id) IS NOT NULL AND MAX(s.element_pedagogique_id) IS NOT NULL THEN 'enseignement'
                WHEN MAX(tp.service_id) IS NOT NULL AND MAX(s.element_pedagogique_id) IS NULL THEN 'enseignement-exterieur'
                WHEN MAX(tp.service_referentiel_id) IS NOT NULL THEN 'referentiel'
                ELSE 'mission'
            END 							    typage,
            MAX(e.id)   					    etape_id,
            e.code 							    etape_code,
            MAX(e.libelle) 					    etape_libelle,
            MAX(ep.id)   					    element_id,
            ep.code 						    element_code,
            MAX(ep.libelle) 				    element_libelle,
            MAX(etab.libelle) 				    etab_libelle,
            MAX(etab.source_code) 				etab_code,
            MAX(s.description)                  enseignement_ext_libelle,
            MAX(fr.id)      				    fonction_id,
            fr.code    						    fonction_code,
            MAX(fr.libelle_long)  			    fonction_libelle,
            MAX(sr.commentaires)                fonction_commentaires,
            MAX(th.id) 				  		    type_heure_id,	 
            CASE WHEN th.code = 'primes' 
                THEN 'primes' 
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
        LEFT JOIN structure str ON   str.id = tp.structure_id
        LEFT JOIN service s ON	s.id = tp.service_id
        LEFT JOIN service_referentiel sr ON	sr.id = tp.service_referentiel_id
        LEFT JOIN structure ssr ON ssr.id = sr.structure_id
        LEFT JOIN element_pedagogique ep ON	ep.id = s.element_pedagogique_id
        LEFT JOIN structure se on ep.structure_id = se.id
        LEFT JOIN etape e ON e.id = ep.etape_id
        LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id 
        LEFT JOIN type_heures th ON th.id = tp.type_heures_id 
        LEFT JOIN mise_en_paiement mep ON mep.id = tp.mise_en_paiement_id AND mep.histo_destruction IS NULL
        LEFT JOIN centre_cout cc ON cc.id = tp.centre_cout_id 
        LEFT JOIN domaine_fonctionnel df ON df.id = tp.domaine_fonctionnel_id
        LEFT JOIN periode p ON p.id = tp.periode_paiement_id
        LEFT JOIN mission m ON m.id = tp.mission_id
        LEFT JOIN structure sm ON sm.id = m.structure_id
        LEFT JOIN type_mission tm ON tm.id = m.type_mission_id
        LEFT JOIN etablissement etab ON etab.id = s.etablissement_id
        LEFT JOIN intervenant i ON i.id = tp.intervenant_id
        LEFT JOIN structure si ON si.id = i.structure_id
        WHERE
            tp.intervenant_id = :intervenant ";

        //Si on ne veut que les heures à payer d'une structure donnée.
        if (!empty($structure)) {
            $sql .= " AND str.ids LIKE :structure";
        }

        $sql .= "
        GROUP BY
            tp.intervenant_id ,
            tp.structure_id,
            tp.mission_id,
            e.code,
            ep.code,
            fr.code,
            tp.service_referentiel_id,
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
            $params['structure'] = $structure->idsFilter();
        }

        $dmeps = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);


        //On récupère la liste des domaines fonctionnels
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
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['exterieur'] = 0;
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['libelle']                                         = $value['ELEMENT_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['libelle'] = $value['TYPE_HEURE_LIBELLE'];
                if (!array_key_exists('heures', $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']])) {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures'] = [];
                }
                //Heure déjà mise en paiement
                if (!empty($value['MEP_ID'])) {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures']['mep_id_' . $value['MEP_ID']] = [
                        'mepId'                => $value['MEP_ID'],
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                } else {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAPE_CODE']]['enseignements'][$value['ELEMENT_CODE']]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures']['a_demander'] = [
                        'mepId'                => '',
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                }
            } elseif ($value['TYPAGE'] == "enseignement-exterieur") {
                $ensCode = 1;
                //Partie enseignements exterieurs
                $dmep[$value['STRUCTURE_CODE']]['code']                                                                                                      = $value['STRUCTURE_CODE'];
                $dmep[$value['STRUCTURE_CODE']]['id']                                                                                                        = $value['STRUCTURE_ID'];
                $dmep[$value['STRUCTURE_CODE']]['libelle']                                                                                                   = $value['STRUCTURE_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['libelleCourt']                                                                                              = $value['STRUCTURE_LIBELLE_COURT'];
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['libelle'] = $value['ETAB_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['exterieur'] = 1;
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['enseignements'][$ensCode]['libelle']                                         = $value['ENSEIGNEMENT_EXT_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['enseignements'][$ensCode]['typeHeure'][$value['TYPE_HEURE_CODE']]['libelle'] = $value['TYPE_HEURE_LIBELLE'];
                if (!array_key_exists('heures', $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['enseignements'][$ensCode]['typeHeure'][$value['TYPE_HEURE_CODE']])) {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['enseignements'][$ensCode]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures'] = [];
                }
                //Heure déjà mise en paiement
                if (!empty($value['MEP_ID'])) {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['enseignements'][$ensCode]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures']['mep_id_' . $value['MEP_ID']] = [
                        'mepId'                => $value['MEP_ID'],
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                } else {
                    $dmep[$value['STRUCTURE_CODE']]['etapes'][$value['ETAB_CODE']]['enseignements'][$ensCode]['typeHeure'][$value['TYPE_HEURE_CODE']]['heures']['a_demander'] = [
                        'mepId'                => '',
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                }
                $ensCode++;
            } elseif ($value['TYPAGE'] == "referentiel") {
                //Partie référentiel
                $keyFonctionReferentiel                                                                           = $value['FONCTION_CODE'] . $value['SERVICE_REFERENTIEL_ID'];
                $dmep[$value['STRUCTURE_CODE']]['code']                                                      = $value['STRUCTURE_CODE'];
                $dmep[$value['STRUCTURE_CODE']]['id']                                                        = $value['STRUCTURE_ID'];
                $dmep[$value['STRUCTURE_CODE']]['libelle']                                                   = $value['STRUCTURE_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['libelleCourt']                                              = $value['STRUCTURE_LIBELLE_COURT'];
                $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$keyFonctionReferentiel]['libelle']      = $value['FONCTION_LIBELLE'];
                $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$keyFonctionReferentiel]['commentaires'] = $value['FONCTION_COMMENTAIRES'];
                if (!array_key_exists('heures', $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$keyFonctionReferentiel])) {
                    $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$keyFonctionReferentiel]['heures'] = [];
                }
                if (!empty($value['MEP_ID'])) {
                    $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$keyFonctionReferentiel]['heures']['mep_id_' . $value['MEP_ID']] = [
                        'mepId'                => $value['MEP_ID'],
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                } else {
                    $dmep[$value['STRUCTURE_CODE']]['fonctionsReferentiels'][$keyFonctionReferentiel]['heures']['a_demander'] = [
                        'mepId'                => '',
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
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
                        'mepId'                => $value['MEP_ID'],
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                } else {
                    $dmep[$value['STRUCTURE_CODE']]['missions'][$value['MISSION_ID']]['heures']['a_demander'] = [
                        'mepId'                => '',
                        'typeHeureId'          => $value['TYPE_HEURE_ID'],
                        'typeHeureCode'        => $value['TYPE_HEURE_CODE'],
                        'serviceId'            => $value['SERVICE_ID'],
                        'serviceReferentielId' => $value['SERVICE_REFERENTIEL_ID'],
                        'missionId'            => $value['MISSION_ID'],
                        'heuresDemandees'      => $value['HEURES_DEMANDEES'],
                        'heuresPayees'         => $value['HEURES_PAYEES'],
                        'heuresAPayer'         => $value['HEURES_A_PAYER'],
                        'periodeLibelle'       => $value['PERIODE_LIBELLE'],
                        'periodeCode'          => $value['PERIODE_CODE'],
                        'datePaiement'         => $value['DATE_PAIEMENT'],
                        'dateDemande'          => $value['DATE_DEMANDE'],
                        'domaineFonctionnel'   => [
                            'domaineFonctionnelId' => $value['DOMAINE_FONCTIONNEL_ID'] ?: '',
                            'libelle'              => $value['DOMAINE_FONCTIONNEL_LIBELLE'] ?: '',
                            'code'                 => $value['DOMAINE_FONCTIONNEL_CODE'] ?: '',
                        ],
                        'centreCout'           => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                }
            }


            /*On alimente les centres couts disponibles pour ces demandes de mise en paiement
            Si le paramétrage est affectation, on va chercher une fois pour toutes les centres de couts de paiement
            de la structure d'affectation de l'intervenant*/
            if ($parametreCentreCout == 'affectation' && empty($centresCoutsPaiementAffectation) && $intervenant->getStructure()) {
                $structure                       = $this->getServiceStructure()->get($intervenant->getStructure()->getId());
                $centresCoutsPaiementAffectation = $this->getServiceCentreCout()->getCentresCoutsMiseEnPaiement($structure);
            }

            if (!array_key_exists('centreCoutPaiement', $dmep[$value['STRUCTURE_CODE']])) {
                $dmep[$value['STRUCTURE_CODE']]['centreCoutPaiement'] = [];
                if ($parametreCentreCout == 'enseignement' || empty($intervenant->getStructure())) {
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
                            'primes'            => $centreCout['PRIMES'],
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
                            'primes'            => $centreCout['PRIMES'],
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
            $budget                                   = $this->getServiceBudget()->getBudgetPaiement($structure);
            $dmep[$value['STRUCTURE_CODE']]['budget'] = $budget;
        }

        return $dmep;
    }



    public function verifierBudgetDemandeMiseEnPaiement(array $demandes): array
    {
        $demandesApprouvees = [];
        $totalHeuresDemandees = [];

        //1 - On récupère le budget de la structure pour laquelle on a des heures à demander
        foreach ($demandes as $demande) {
            $structure = $this->getEntityManager()->getRepository(Structure::class)->find($demande['structureId']);
            $structure = $this->getServiceStructure()->get($demande['structureId']);
            if ($structure instanceof Structure) {

                $budget = $this->getServiceBudget()->getBudgetPaiement($structure);

                //2 - On récupère le centre de cout que nous souhaitons utiliser pour la demande de mise en paiement

                $centreCout = $this->getEntityManager()->getRepository(CentreCout::class)->find($demande['centreCoutId']);
                $centreCout = $this->getServiceCentreCout()->get($demande['centreCoutId']);
                if ($centreCout instanceof CentreCout) {
                    //3 - on vérifier si il y a du budget dans le type de ressource auquel est rattaché ce centre de cout
                    if (array_key_exists($centreCout->getTypeRessource()->getCode(), $budget['dotation'])) {
                        if (!array_key_exists($centreCout->getTypeRessource()->getCode(), $totalHeuresDemandees)) {
                            $totalHeuresDemandees[$centreCout->getTypeRessource()->getCode()] = 0;
                        }
                        if ($budget['dotation'][$centreCout->getTypeRessource()->getCode()]['heures'] > 0) {

                            //4 - on regarde si il y a encore assez de budget pour demander les heures en paiement
                            $total = round($budget['consommation'][$centreCout->getTypeRessource()->getCode()]['heures'] + $demande['heures'] + $totalHeuresDemandees[$centreCout->getTypeRessource()->getCode()], 2);
                            if ($total <= $budget['dotation'][$centreCout->getTypeRessource()->getCode()]['heures']) {
                                $totalHeuresDemandees[$centreCout->getTypeRessource()->getCode()] += $demande['heures'];
                            } else {
                                continue;
                            }
                        }
                        $demandesApprouvees[] = $demande;
                    } else {
                        //Si pas de dotation dans ce type de ressources alors on autorise obligatoirement la demande
                        $demandesApprouvees[] = $demande;
                    }
                } else {
                    $demandesApprouvees[] = $demande;
                }
            }
        }

        return $demandesApprouvees;
    }



    public function verifierValiditeDemandeMiseEnPaiement(Intervenant $intervenant, array $data): bool
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
        $heuresDemandees      = $data['heures'];
        $serviceId            = $data['serviceId'];
        $serviceReferentielId = $data['serviceReferentielId'];
        $missionId            = $data['missionId'];
        foreach ($dmeps as $dmep) {
            $totalHeuresDemandees += $dmep['TOTAL_HEURES_DEMANDEES'];
            $totalHeuresAPayer    += $dmep['TOTAL_HEURES_A_PAYER'];
            if ($serviceReferentielId === $dmep['SERVICE_REFERENTIEL_ID'] || $serviceId === $dmep['SERVICE_ID'] || $missionId === $dmep['MISSION_ID']) {
                $soldeHeures = round($dmep['TOTAL_HEURES_A_PAYER'] - $dmep['TOTAL_HEURES_DEMANDEES'], 2);
                if (bccomp((string)$heuresDemandees, (string)$soldeHeures, 2) > 0) {
                    if ($soldeHeures >= 0) {
                        throw new \Exception('Demande de mise en paiement impossible, vous demandez ' . $heuresDemandees . ' hetd(s) alors que vous pouvez demander maximum ' . ($dmep['TOTAL_HEURES_A_PAYER'] - $dmep['TOTAL_HEURES_DEMANDEES']) . ' hetd(s)', self::EXCEPTION_DMEP_INVALIDE);
                    } else {
                        throw new \Exception('Demande de mise en paiement impossible, vous demandez ' . $heuresDemandees . ' hetd(s) alors que vous avez déjà payé la totalité des heures pour cet enseignement (' . $dmep['TOTAL_HEURES_A_PAYER'] . ' hetd(s))', self::EXCEPTION_DMEP_INVALIDE);
                    }
                }
            }
        }
        $totalHeuresAPayer    = round($totalHeuresAPayer, 2);
        $totalHeuresDemandees = round($totalHeuresDemandees, 2);
        //On  vérifie qu'il y a bien un centre de cout
        if (empty($data['centreCoutId'])) {
            throw new \Exception('Vous devez renseigner un centre de coûts pour demander ce paiement', self::EXCEPTION_DMEP_CENTRE_COUT);
        }
        //On vérifie qu'il y a bien un domaine fonctionnel dans le cadre du référentiel et des missions
        if (!empty($data['missionId']) || !empty($data['formuleResServiceRefId'])) {
            if (empty($data['domaineFonctionnelId'])) {
                throw new \Exception('Vous devez renseigner un domaine fonctionnel pour demander ce paiement', self::EXCEPTION_DMEP_DOMAINE_FONCTIONNEL);
            }
        }
        $soldeTotalHeures = round($totalHeuresAPayer - $totalHeuresDemandees, 2);
        //On vérifie en dernier si l'ensemble des heures déjà payé ne dépasse pas le nombre d'heures réalisées tout service confondu.
        if (bccomp((string)($soldeTotalHeures), (string)$heuresDemandees, 2) < 0) {
            throw new \Exception('Demande de mise en paiement impossible, la somme des heures déjà demandée en paiement pour tous les services confondus ne permet plus de demander en paiement les ' . $heuresDemandees . ' hetd(s)', self::EXCEPTION_DMEP_INVALIDE);
        }


        return true;
    }



    public function getListByStructure(Structure $structure): array
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
        $intervenantPaiementErreur = [];
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
            /*Dés qu'on a une ligne de paiement avec plus d'heures demandées que d'heures à payer on sort
            l'intervenant du traitement de paiement en masse, il faudra faire un paiement manuel */
            if ($value->getHeuresAPayer() < $value->getHeuresDemandees()) {
                $intervenantPaiementErreur[] = $intervenant->getId();
            }

            if (empty($value->getMiseEnPaiement())) {
                $workflowEtape = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_DEMANDE_MEP, $intervenant, $value->getStructure());
                //Si l'étape de demande de mise en paiement n'est pas atteignable alors on ne le propose pas
                if (!$workflowEtape || !$workflowEtape->isAtteignable()) {
                    continue;
                }
                //On ne prend pas le référentiel dans les demandes de mise en paiement en lot
                if (!$value->getServiceReferentiel()) {
                    if (!array_key_exists($intervenant->getId(), $dmep)) {

                        $dmep[$intervenant->getId()]['datasIntervenant'] = [
                            'id'              => $intervenant->getId(),
                            'code'            => $intervenant->getCode(),
                            'nom_usuel'       => $intervenant->getNomUsuel(),
                            'prenom'          => $intervenant->getPrenom(),
                            'structure'       => ($intervenant->getStructure()) ? $intervenant->getStructure()->getLibelleCourt() : '',
                            'statut'          => $intervenant->getStatut()->getLibelle(),
                            'typeIntervenant' => $intervenant->getStatut()->getTypeIntervenant()->getLibelle(),
                            'incoherencePaiement' => false,
                            'totalHeures'         => 0,
                        ];
                    }


                    //On prend uniquement les heures ou hetd qui ne sont pas du référentiel

                    $dmep[$intervenant->getId()]['heures'][] = [
                        'heuresAPayer'       => $value->getHeuresAPayerAC() + $value->getHeuresAPayerAA(),
                        'missionId'          => ($value->getMission()) ? $value->getMission()->getId() : '',
                        'serviceId'          => ($value->getService()) ? $value->getService()->getId() : '',
                        'serviceRefId'       => ($value->getServiceReferentiel()) ? $value->getServiceReferentiel()->getId() : '',
                        'centreCout'         => ['libelle'              => ($value->getCentreCout()) ? $value->getCentreCout()->getLibelle() : '',
                                                 'code'                 => ($value->getCentreCout()) ? $value->getCentreCout()->getCode() : '',
                                                 'typeRessourceCode'    => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getCode() : '',
                                                 'typeRessourceLibelle' => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getLibelle() : '',
                        ],
                        'domaineFonctionnel' => ['libelle' => ($value->getDomaineFonctionnel()) ? $value->getDomaineFonctionnel()->getLibelle() : '',
                                                 'code'    => ($value->getDomaineFonctionnel()) ? $value->getDomaineFonctionnel()->getSourceCode() : '',
                        ],
                    ];
                }
            }
        }

        /*on flaggue les intervenants avec des paiements incohérent (ligne trop payée, etc...) pour ne pas les traiter dans le
        paiement en masse mais quand même indiquer qu'il y a des problèmes sur les demandes de mise
        en paiement de cet intervenant, traitement manuelle nécessaire*/
        foreach ($intervenantsTotalHeures as $intervenantId => $heures) {
            if (array_key_exists($intervenantId, $dmep)) {
                $dmep[$intervenantId]['datasIntervenant']['totalHeures'] = $heures['heuresAPayer'] - $heures['heuresDemandees'];
                if ($heures['heuresDemandees'] >= $heures['heuresAPayer']) {
                    $dmep[$intervenantId]['datasIntervenant']['incoherencePaiement'] = true;
                    unset($dmep[$intervenantId]);
                }
            }
        }
        /*on flaggue les intervenants avec des paiements incohérent (ligne trop payée, etc...) pour ne pas les traiter dans le
        paiement en masse mais quand même indiquer qu'il y a des problèmes sur les demandes de mise
        en paiement de cet intervenant, traitement manuelle nécessaire*/
        foreach ($intervenantPaiementErreur as $intervenantId) {
            if (array_key_exists($intervenantId, $dmep)) {
                $dmep[$intervenantId]['datasIntervenant']['incoherencePaiement'] = true;
            }
        }

        return $dmep;
    }

}