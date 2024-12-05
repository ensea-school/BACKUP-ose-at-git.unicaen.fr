<?php

namespace Formule\Controller;

use Application\Controller\AbstractController;
use Formule\Service\AfficheurServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;

class  AffichageController extends AbstractController
{
    use AfficheurServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;

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

        $data = [];

        $res = $this->em()->getConnection()->fetchAllAssociative($sql, ['intervenant' => $intervenant->getId()]);

        foreach ($res as $r) {
            $tvhId = (int)$r['TYPE_VOLUME_HORAIRE_ID'];
            $tvhLib = $r['TYPE_VOLUME_HORAIRE_LIBELLE'];
            $evhId = (int)$r['ETAT_VOLUME_HORAIRE_ID'];
            $evhLib = $r['ETAT_VOLUME_HORAIRE_LIBELLE'];

            if (!isset($data[$tvhId])) {
                $data[$tvhId] = [
                    'libelle' => $tvhLib,
                    'etats'   => [],
                ];
            }
            $data[$tvhId]['etats'][$evhId] = $evhLib;
        }

        return compact('intervenant', 'data');
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

        return new AxiosModel( compact('data'));
    }
}
