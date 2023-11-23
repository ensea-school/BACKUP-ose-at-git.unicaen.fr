<?php

namespace Paiement\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractService;
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
            tp. intervenant = :intervenant
        ";
        /** @var TblPaiement[] $meps */
        $meps = $this->getEntityManager()->createQuery($dql)->setParameters(['intervenant' => $intervenant])->getResult();

        $saps = [];
        foreach ($meps as $mep) {
            if ($mep->getHeuresAPayer() > 0 || $mep->getMiseEnPaiement()) {
                $sap = $mep->getServiceAPayer();
                $sapId = get_class($sap) . '@' . $sap->getId();
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
                $intervenant = $value->getIntervenant();
                if (!array_key_exists($intervenant->getId(), $dmep)) {

                    $dmep[$intervenant->getId()]['datasIntervenant'] = [
                        'nom_usuel' => $intervenant->getNomUsuel(),
                        'prenom'    => $intervenant->getPrenom(),
                        'structure' => $intervenant->getStructure()->getLibelleCourt(),
                        'statut'    => $intervenant->getStatut()->getLibelle(),
                    ];
                }
                $dmep[$intervenant->getId()]['heures'][] = [
                    'heuresAPayer' => $value->getHeuresAPayerAC() + $value->getHeuresAPayerAA(),
                    'centreCout'   => '',
                ];
            }
        }

        return $dmep;
    }
}