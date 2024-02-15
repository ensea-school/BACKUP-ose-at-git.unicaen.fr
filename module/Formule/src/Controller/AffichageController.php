<?php

namespace Formule\Controller;

use Application\Controller\AbstractController;
use Formule\Form\HeuresCompFormAwareTrait;
use Formule\Service\FormuleResultatServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use LogicException;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;

class  AffichageController extends AbstractController
{
    use HeuresCompFormAwareTrait;
    use FormuleResultatServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;


    public function voirHeuresCompAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $form = $this->getFormIntervenantHeuresComp();

        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQuery('type-volume-horaire', $form->get('type-volume-horaire')->getValue());
        if (!$typeVolumeHoraire instanceof TypeVolumeHoraire) {
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->get($typeVolumeHoraire);
        }
        /* @var $typeVolumeHoraire TypeVolumeHoraire */
        if (!isset($typeVolumeHoraire)) {
            throw new LogicException('Type de volume horaire erroné');
        }

        $etatVolumeHoraire = $this->context()->etatVolumeHoraireFromQuery('etat-volume-horaire', $form->get('etat-volume-horaire')->getValue());
        if (!$etatVolumeHoraire instanceof EtatVolumeHoraire) {
            $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->get($etatVolumeHoraire);
        }
        /* @var $etatVolumeHoraire EtatVolumeHoraire */
        if (!isset($etatVolumeHoraire)) {
            throw new LogicException('Etat de volume horaire erroné');
        }

        $form->setData([
            'type-volume-horaire' => $typeVolumeHoraire->getId(),
            'etat-volume-horaire' => $etatVolumeHoraire->getId(),
        ]);

        $data = $this->getServiceFormuleResultat()->getData(
            $intervenant,
            $typeVolumeHoraire,
            $etatVolumeHoraire
        );

        return compact('form', 'intervenant', 'data');
    }



    public function formuleTotauxHetdAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');
        $formuleResultat = $intervenant->getFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return compact('formuleResultat');
    }
}
