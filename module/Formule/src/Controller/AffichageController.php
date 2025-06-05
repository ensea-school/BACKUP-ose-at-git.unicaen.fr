<?php

namespace Formule\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Formule\Model\FormuleDetailsExtractor;
use Formule\Service\AfficheurServiceAwareTrait;
use Formule\Service\FormuleServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;

class  AffichageController extends AbstractController
{
    use AfficheurServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use FormuleServiceAwareTrait;

    public function detailsAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $sql = "
        SELECT
          tvh.id type_volume_horaire_id,
          tvh.libelle type_volume_horaire_libelle,
          evh.id etat_volume_horaire_id,
          evh.libelle etat_volume_horaire_libelle
        FROM
          formule_resultat_intervenant fri
          JOIN type_volume_horaire tvh ON tvh.id = fri.type_volume_horaire_id
          JOIN etat_volume_horaire evh ON evh.id = fri.etat_volume_horaire_id
        WHERE
          intervenant_id = :intervenant
        ORDER BY
          tvh.ordre, evh.ordre            
        ";

        $res = $this->em()->getConnection()->fetchAllAssociative($sql, ['intervenant' => $intervenant->getId()]);

        $typesVolumesHoraires = [];
        foreach ($res as $r) {
            $tvhId  = (int)$r['TYPE_VOLUME_HORAIRE_ID'];
            $tvhLib = $r['TYPE_VOLUME_HORAIRE_LIBELLE'];
            $evhId  = (int)$r['ETAT_VOLUME_HORAIRE_ID'];
            $evhLib = $r['ETAT_VOLUME_HORAIRE_LIBELLE'];

            if (!isset($typesVolumesHoraires[$tvhId])) {
                $typesVolumesHoraires[$tvhId] = [
                    'libelle' => $tvhLib,
                    'etats'   => [],
                ];
            }
            $typesVolumesHoraires[$tvhId]['etats'][$evhId] = $evhLib;
        }

        $canReporter = $this->isAllowed(Privileges::getResourceId(Privileges::FORMULE_TESTS));

        return compact('intervenant', 'typesVolumesHoraires', 'canReporter');
    }



    public function detailsDataAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        /** @var $typeVolumeHoraire */
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');

        /** @var $etatVolumeHoraire */
        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');

        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        if (!$typeVolumeHoraire) {
            throw new \LogicException('Type de volume horaire non précisé ou inexistant');
        }

        if (!$etatVolumeHoraire) {
            throw new \LogicException('Etat de volume horaire non précisé ou inexistant');
        }

        $fi = $this->getServiceFormule()->getFormuleServiceIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

        $extractor = new FormuleDetailsExtractor();
        $data      = $extractor->extract($fi);

        return new AxiosModel($data);
    }



    public function formuleTotauxHetdAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /** @var $intervenant Intervenant */

        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        /** @var $typeVolumeHoraire TypeVolumeHoraire */

        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getSaisi();
        $formuleResultat   = $intervenant->getFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        $data = $this->getServiceAfficheur()->resultatToJson($formuleResultat);

        return new AxiosModel(compact('data'));
    }
}
