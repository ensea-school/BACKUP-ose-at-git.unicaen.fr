<?php

namespace Enseignement\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\MotifNonPaiement;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Enseignement\Entity\VolumeHoraireListe;
use Application\Form\AbstractForm;
use Enseignement\Form\VolumeHoraireSaisieCalendaireForm;
use Enseignement\Form\VolumeHoraireSaisieCalendaireFormAwareTrait;
use Enseignement\Form\VolumeHoraireSaisieForm;
use Enseignement\Form\VolumeHoraireSaisieFormAwareTrait;
use Enseignement\Hydrator\ListeFilterHydrator;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use RuntimeException;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of VolumeHoraireController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireController extends AbstractController
{
    use ContextServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use VolumeHoraireSaisieFormAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;
    use VolumeHoraireSaisieCalendaireFormAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;


    public function listeAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            VolumeHoraire::class,
        ]);
        $service = $this->getEvent()->getParam('service');
        /* @var $service Service */
        if (!$service) throw new RuntimeException("Service non spécifié ou introuvable.");

        $typeVolumeHoraireId = $this->params()->fromPost('type-volume-horaire', $this->params()->fromQuery('type-volume-horaire'));
        $typeVolumeHoraire   = $this->getServiceTypeVolumeHoraire()->get($typeVolumeHoraireId);


        $service->setTypeVolumeHoraire($typeVolumeHoraire);
        $readOnly = 1 == (int)$this->params()->fromQuery('read-only', 0);

        $volumeHoraireListe = $service->getVolumeHoraireListe()->setTypeVolumehoraire($typeVolumeHoraire);
        $semestriel         = $this->getServiceContext()->isModaliteServicesSemestriel($typeVolumeHoraire);

        return compact('volumeHoraireListe', 'readOnly', 'semestriel');
    }



    public function saisieAction()
    {
        return $this->saisieMixte($this->getFormVolumeHoraireSaisie());
    }



    public function saisieCalendaireAction()
    {
        return $this->saisieMixte($this->getFormVolumeHoraireSaisieCalendaire());
    }



    private function saisieMixte(AbstractForm $form)
    {
        
        $this->em()->getFilters()->enable('historique')->init([
            VolumeHoraire::class,
            MotifNonPaiement::class,
            Tag::class,
        ]);

        /** @var Service $service */
        $service = $this->getEvent()->getParam('service');


        if (!$service) {
            throw new \Exception('Service non fourni');
        }

        $volumeHoraireListe = new VolumeHoraireListe($service);

        $vhlph = new ListeFilterHydrator();
        $vhlph->setEntityManager($this->em());

        $vhlph->hydrate($this->params()->fromQuery() + $this->params()->fromPost(), $volumeHoraireListe);
        $service->setTypeVolumeHoraire($volumeHoraireListe->getTypeVolumeHoraire());

        $canViewMNP = $this->isAllowed($service->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_VISUALISATION);
        $canEditMNP = $canViewMNP && $this->isAllowed($service->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_EDITION);
        $canViewTag = $this->isAllowed(Privileges::getResourceId(Privileges::TAG_VISUALISATION));
        $canEditTag = $canViewTag && $this->isAllowed(Privileges::getResourceId(Privileges::TAG_EDITION));

        $hDeb = $volumeHoraireListe->getHeures();

        /**
         * @var VolumeHoraireSaisieForm $form
         */

        $form->setViewMNP($canViewMNP);
        $form->setEditMNP($canEditMNP);
        $form->setViewTag($canViewTag);
        $form->setEditTag($canEditTag);
        $form->build();
        $bind = $form->bindRequestSave($volumeHoraireListe, $this->getRequest(), function (VolumeHoraireListe $vhl) use ($hDeb, $volumeHoraireListe) {
            try {
                $service = $vhl->getService();
                $this->getProcessusPlafond()->beginTransaction();
                $this->getServiceService()->save($service);
                $hFin = $volumeHoraireListe->getHeures();
                $this->updateTableauxBord($service->getIntervenant());
                if (!$this->getProcessusPlafond()->endTransaction($service->getIntervenant(), $vhl->getTypeVolumeHoraire(), $hFin < $hDeb)) {
                    $this->updateTableauxBord($service->getIntervenant());
                } else {
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form');
    }



    public function suppressionCalendaireAction()
    {
        /** @var Service $service */
        $service = $this->getEvent()->getParam('service');

        if (!$service) {
            throw new \Exception('Service non fourni');
        }

        $volumeHoraireListe = new VolumeHoraireListe($service);
        $vhlph              = new ListeFilterHydrator();
        $vhlph->setEntityManager($this->em());
        $vhlph->hydrate($this->params()->fromQuery(), $volumeHoraireListe);

        $service->setTypeVolumeHoraire($volumeHoraireListe->getTypeVolumeHoraire());
        $volumeHoraireListe->setHeures(0);

        try {
            $this->getProcessusPlafond()->beginTransaction();
            $this->getServiceService()->save($service);
            $this->updateTableauxBord($service->getIntervenant());
            $this->getProcessusPlafond()->endTransaction($service->getIntervenant(), $volumeHoraireListe->getTypeVolumeHoraire(), true);
            $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule', 'validation_enseignement', 'service', 'piece_jointe_fournie',
        ], $intervenant);
    }

}