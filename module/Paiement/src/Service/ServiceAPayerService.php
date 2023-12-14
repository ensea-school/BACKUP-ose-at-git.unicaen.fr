<?php

namespace Paiement\Service;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractService;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\CentreCout;
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
    public function getListByIntervenantNew (Intervenant $intervenant)
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
        $dmeps = $this->getEntityManager()->createQuery($dql)->setParameters(['intervenant' => $intervenant])->getResult();
        $dmep  = [];

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
            //On traite ici les heures d'enseignement
            if ($etapeEnseignement) {
                $dmep[$structureEnseignement->getCode()]['libelle']                                                                                                                                                                       = $structureEnseignement->getLibelle();
                $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['libelle']                                                                                                                              = $etapeEnseignement->getLibelle();
                $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['libelle']                                                                 = $elementPedagogiqueEnseignement->getLibelle();
                $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['libelle']                 = $typeHeureEnseignement->getLibelleLong();
                $dmep[$structureEnseignement->getCode()]['etapes'][$etapeEnseignement->getCode()]['enseignements'][$elementPedagogiqueEnseignement->getCode()]['typeHeure'][$typeHeureEnseignement->getCode()]['heures'][$value->getId()] = [
                    'heuresAPayer'   => $value->getHeuresAPayerAC() + $value->getHeuresAPayerAA(),
                    'heuresPayer'    => $value->getHeuresPayees(),
                    'heuresDemander' => $value->getHeuresDemandees(),
                    'centreCout'     => ['libelle'              => ($value->getCentreCout()) ? $value->getCentreCout()->getLibelle() : '',
                                         'code'                 => ($value->getCentreCout()) ? $value->getCentreCout()->getCode() : '',
                                         'typeRessourceCode'    => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getCode() : '',
                                         'typeRessourceLibelle' => ($value->getCentreCout()) ? $value->getCentreCout()->getTypeRessource()->getLibelle() : '',
                    ],
                ];
            }
            //On traite les heures de référentiels
            if ($fonctionReferentiel) {

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