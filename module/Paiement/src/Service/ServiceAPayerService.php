<?php

namespace Paiement\Service;

use Application\Entity\Db\WfEtape;
use Application\Service\AbstractService;
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


    /**
     *
     * @param Intervenant $intervenant
     *
     * @return ServiceAPayerInterface[]
     */
    public function getListByIntervenant(Intervenant $intervenant)
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
}