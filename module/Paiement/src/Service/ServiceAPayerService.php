<?php

namespace Paiement\Service;

use Application\Entity\Db\WfEtape;
use Application\Service\AbstractService;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\TblPaiement;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of ServiceAPayer
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceAPayerService extends AbstractService
{
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use ParametresServiceAwareTrait;


    /**
     *
     * @param Intervenant $intervenant
     *
     * @return ServiceAPayerInterface[]
     */
    public function getDemandeMiseEnPaiementResume (Intervenant $intervenant)
    {
        $sql = "
        
        SELECT
            tp.intervenant_id 				    intervenant_id,
            tp.structure_id                     structure_id,
            MAX(s.code)                         structure_code,
            MAX(s.libelle_long)   			    structure_libelle,
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
            th.code               			    type_heure_code,
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
            MAX(tp.formule_res_service_id)      formule_res_service_id,
            MAX(tp.formule_res_service_ref_id)  formule_res_service_ref_id,
            MAX(tp.mission_id)                  mission_id,
            MAX(tp.type_heures_id )             type_heure_id,
            MAX(th.code)                        type_heure_code
        FROM
            tbl_paiement tp
        LEFT JOIN structure s ON s.id = tp.structure_id 
        LEFT JOIN service s ON	s.id = tp.service_id
        LEFT JOIN service_referentiel sr ON	sr.id = tp.service_referentiel_id
        LEFT JOIN element_pedagogique ep ON	ep.id = s.element_pedagogique_id
        LEFT JOIN etape e ON e.id = ep.etape_id
        LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id 
        LEFT JOIN type_heures th ON th.id = tp.type_heures_id 
        LEFT JOIN mise_en_paiement mep ON mep.id = tp.mise_en_paiement_id 
        LEFT JOIN centre_cout cc ON cc.id = tp.centre_cout_id 
        LEFT JOIN periode p ON p.id = tp.periode_paiement_id
        WHERE
            tp.intervenant_id = :intervenant
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
            MAX(fr.libelle_long) ASC,
            tp.mise_en_paiement_id ASC

        ";

        $dmeps = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, [
            'intervenant' => $intervenant->getId(),
        ]);

        //On regarde le paramétrage des centres de cout pour les paiement
        $parametreCentreCout = $this->getServiceParametres()->get('centres_couts_paye');
        //Si le paramétrage est affectation, on va chercher une fois pour toutes les centres de couts de paiement de la structure d'affectation de l'intervenant
        if ($parametreCentreCout == 'affectation') {
            $structure                       = $this->getEntityManager()->getRepository(Structure::class)->find($intervenant->getStructure()->getId());
            $centresCoutsPaiementAffectation = $this->getServiceCentreCout()->getCentresCoutsMiseEnPaiement($structure);
        }


        foreach ($dmeps as $value) {
            //On va chercher les centre de cout nécessaire aux mises en paiement
            //On traite ici les heures d'enseignement
            if ($value['TYPAGE'] == "enseignement") {

                $dmep[$value['STRUCTURE_CODE']]['libelle']                                                                                                                  = $value['STRUCTURE_LIBELLE'];
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
                        'domaineFonctionelId'    => $value['DOMAINE_FONCTIONNEL_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
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
                        'domaineFonctionelId'    => $value['DOMAINE_FONCTIONNEL_ID'],
                        'missionId'              => $value['MISSION_ID'],
                        'heuresAPayer'           => $value['HEURES_A_PAYER'],
                        'heuresDemandees'        => $value['HEURES_DEMANDEES'],
                        'heuresPayees'           => $value['HEURES_PAYEES'],
                        'centreCout'             => [
                            'centreCoutId'         => $value['CENTRE_COUT_ID'] ?: '',
                            'libelle'              => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'code'                 => $value['CENTRE_COUT_CODE'] ?: '',
                            'typeRessourceCode'    => $value['CENTRE_COUT_LIBELLE'] ?: '',
                            'typeRessourceLibelle' => $value['CENTRE_COUT_LIBELLE'] ?: '',
                        ],
                    ];
                }
            } else {
                //on fera le referentiel plus tard
                continue;
            }
            //On va récuperer les centres de couts de la structure d'enseignement
            //1- On regarde le paramètrage des centres de coût pour la paye (affectation ou enseignement)
            $structureId = $value['STRUCTURE_CODE'];


            $centresCouts = $this->getServiceCentreCout()->getCentresCoutsMiseEnPaiement($structure);
            //Je n'ai pas encore fourni la liste des centres de couts utilisable pour les demandes de mise en paiement
            if (!array_key_exists('centreCoutPaiement', $dmep[$value['STRUCTURE_CODE']])) {
                $dmep[$value['STRUCTURE_CODE']]['centreCoutPaiement'] = [];
                if ($parametreCentreCout == 'enseignement') {
                    $structure    = $this->getEntityManager()->getRepository(Structure::class)->find($value['STRUCTURE_ID']);
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
                            'referentiel'       => $centreCout['REFERENTIEL'],
                            'fcMajorees'        => $centreCout['FC_MAJOREES'],
                        ];
                    } else {
                        $listeCentresCouts['AUTRES'][] = [
                            'centreCoutId'      => $centreCout['CENTRE_COUT_ID'],
                            'centreCoutLibelle' => $centreCout['LIBELLE'],
                            'centreCoutCode'    => $centreCout['CODE'],
                            'fi'                => $centreCout['FI'],
                            'fa'                => $centreCout['FA'],
                            'fc'                => $centreCout['FC'],
                        ];
                    }

                    $dmep[$value['STRUCTURE_CODE']]['centreCoutPaiement'] = $listeCentresCouts;
                }
            }
        }


        return $dmep;
    }



    /**
     *
     * @param Intervenant $intervenant
     *
     * @return ServiceAPayerInterface[]
     */
    public function getListByIntervenantNew (Intervenant $intervenant)
    {
        $dql = "
        SELECT
            tp,
            count(tp.periodeEnseignement)
        FROM
            " . TblPaiement::class . " tp
        WHERE
            tp.intervenant = :intervenant
        GROUP BY tp.periodeEnseignement
        ";
        /** @var TblPaiement[] $meps */
        //  $dmeps = $this->getEntityManager()->createQuery($dql)->setParameters(['intervenant' => $intervenant])->getResult();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('tp')
            ->from(TblPaiement::class, 'tp')
            ->where('tp.intervenant = :intervenant')
            ->setParameter('intervenant', $intervenant);


        //->groupBy('tp.periodeEnseignement');

        $dmep  = [];
        $dmeps = $qb->getQuery()->getResult();


        foreach ($dmeps as $value) {
            /**
             * @var TblPaiement $value
             */
            $intervenant                    = $value->getIntervenant();
            $serviceAPayer                  = $value->getServiceAPayer();
            $structureIntervenant           = $intervenant->getStructure();
            $structureEnseignement          = $value->getStructure();
            $fonctionReferentiel            = ($value->getFormuleResultatServiceReferentiel()) ? $value->getFormuleResultatServiceReferentiel()->getServiceReferentiel()->getFonctionReferentiel() : null;
            $elementPedagogiqueEnseignement = ($value->getFormuleResultatService()) ? $value->getFormuleResultatService()->getService()->getElementPedagogique() : null;
            $etapeEnseignement              = ($elementPedagogiqueEnseignement) ? $elementPedagogiqueEnseignement->getEtape() : null;
            $typeHeureEnseignement          = $value->getTypeHeures();
            $miseEnPaiement                 = $value->getMiseEnPaiement();

            //On traite ici les heures d'enseignement
            if ($etapeEnseignement) {
                $dmep[$structureEnseignement->getCode()]['libelle']                                                                                                                                                       = $structureEnseignement->getLibelle();
                $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['libelle']                                                                                                              = $etapeEnseignement->getLibelle();
                $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['libelle']                                                 = $elementPedagogiqueEnseignement->getLibelle();
                $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['libelle'] = $typeHeureEnseignement->getLibelleLong();
                if (!array_key_exists('heures', $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()])) {
                    $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['heures'] = [];
                }
                //Heure déjà mise en paiement
                if ($miseEnPaiement) {
                    if (!array_key_exists($miseEnPaiement->getId(), $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['heures'])) {
                        $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['heures'][$miseEnPaiement->getId()] = [
                            'heure'      => 0,
                            'centreCout' => [
                                'libelle'              => ($value->getCentreCout()) ? $value->getCentreCout()->getLibelle() : '',
                                'code'                 => ($value->getCentreCout()) ? $value->getCentreCout()->getCode() : '',
                                'typeRessourceCode'    => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getCode() : '',
                                'typeRessourceLibelle' => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getLibelle() : '',
                            ],
                        ];
                    }
                    $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['heures'][$miseEnPaiement->getId()]['heure'] += $value->getHeuresDemandees();
                } else {
                    $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['heures']['autres'] = [
                        'heure'      => $value->getHeuresDemandees(),
                        'centreCout' => [
                            'libelle'              => ($value->getCentreCout()) ? $value->getCentreCout()->getLibelle() : '',
                            'code'                 => ($value->getCentreCout()) ? $value->getCentreCout()->getCode() : '',
                            'typeRessourceCode'    => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getCode() : '',
                            'typeRessourceLibelle' => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getLibelle() : '',
                        ],
                    ];
                }
            }
        }

        return $dmep;
    }



    /**
     *
     * @param Intervenant $intervenant
     *
     * @return ServiceAPayerInterface[]
     */
    public function getListByIntervenant (Intervenant $intervenant)
    {
        $dql = "
        SELECT
            tp
        FROM
            " . TblPaiement::class . " tp
        WHERE
            tp.intervenant = :intervenant
        ";
        /** @var TblPaiement[] $meps */
        $meps = $this->getEntityManager()->createQuery($dql)->setParameters(['intervenant' => $intervenant])->getResult();

        $saps = [];
        foreach ($meps as $mep) {
            if ($mep->getHeuresAPayer() > 0 || $mep->getMiseEnPaiement()) {
                $sap          = $mep->getServiceAPayer();
                $sapId        = get_class($sap) . '@' . $sap->getId();
                $saps[$sapId] = $sap;
            }
        }

        return $saps;
    }



    public function getListByStructure (Structure $structure)
    {
        $dql = "
        SELECT
            tp
        FROM
            " . TblPaiement::class . " tp
        WHERE
            tp. structure = :structure
        AND tp.annee = :annee
        ";

        /** @var TblPaiement[] $meps */
        $annee = $this->getServiceContext()->getAnnee();

        $dmeps = $this->getEntityManager()->createQuery($dql)->setParameters(['structure' => $structure, 'annee' => $annee])->getResult();

        $dmep = [];

        foreach ($dmeps as $value) {
            /**
             * @var TblPaiement $value
             */
            if (empty($value->getMiseEnPaiement())) {
                $intervenant   = $value->getIntervenant();
                $serviceAPayer = $value->getServiceAPayer();
                $structure     = $intervenant->getStructure();
                $workflowEtape = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_DEMANDE_MEP, $intervenant, $value->getStructure());
                //Si l'étape de demande de mise en paiement n'est pas atteignable alors on ne le propose pas
                if (!$workflowEtape || !$workflowEtape->isAtteignable()) {
                    continue;
                }
                if (!array_key_exists($intervenant->getId(), $dmep)) {

                    $dmep[$intervenant->getId()]['datasIntervenant'] = [
                        'id'              => $intervenant->getId(),
                        'code'            => $intervenant->getCode(),
                        'nom_usuel'       => $intervenant->getNomUsuel(),
                        'prenom'          => $intervenant->getPrenom(),
                        'structure'       => $intervenant->getStructure()->getLibelleCourt(),
                        'statut'          => $intervenant->getStatut()->getLibelle(),
                        'typeIntervenant' => $intervenant->getStatut()->getTypeIntervenant()->getLibelle(),
                    ];
                }

                $dmep[$intervenant->getId()]['heures'][] = [
                    'heuresAPayer' => $value->getHeuresAPayerAC() + $value->getHeuresAPayerAA(),
                    'centreCout'   => ['libelle'              => ($value->getCentreCout()) ? $value->getCentreCout()->getLibelle() : '',
                                       'code'                 => ($value->getCentreCout()) ? $value->getCentreCout()->getCode() : '',
                                       'typeRessourceCode'    => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getCode() : '',
                                       'typeRessourceLibelle' => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getLibelle() : '',
                    ],
                ];
            }
        }

        return $dmep;
    }
}