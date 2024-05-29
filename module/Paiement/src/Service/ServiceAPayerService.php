<?php

namespace Paiement\Service;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractService;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
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
        ORDER BY i.prenom ASC
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
                        'structure'       => ($intervenant->getStructure()) ? $intervenant->getStructure()->getLibelleCourt() : '',
                        'statut'          => $intervenant->getStatut()->getLibelle(),
                        'typeIntervenant' => $intervenant->getStatut()->getTypeIntervenant()->getLibelle(),
                    ];
                }
                //On prend uniquement les heures ou hetd qui ne sont pas du référentiel
                if (!$value->getFormuleResultatServiceReferentiel()) {
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

        return $dmep;
    }
}