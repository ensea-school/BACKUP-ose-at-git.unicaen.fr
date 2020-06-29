<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\VolumeHoraireListe;
use Application\Form\AbstractForm;
use Application\Form\VolumeHoraire\Traits\SaisieAwareTrait;
use Application\Form\VolumeHoraire\Traits\SaisieCalendaireAwareTrait;
use Application\Hydrator\VolumeHoraire\ListeFilterHydrator;
use Application\Processus\Traits\PlafondProcessusAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
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
    use SaisieAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;
    use SaisieCalendaireAwareTrait;


    public function listeAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\VolumeHoraire::class,
        ]);
        $service = $this->getEvent()->getParam('service');
        /* @var $service Service */
        if (!$service) throw new RuntimeException("Service non spécifié ou introuvable.");

        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQueryPost('type-volume-horaire');

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
            \Application\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\MotifNonPaiement::class,
        ]);

        /** @var Service $service */
        $service = $this->getEvent()->getParam('service');

        if (!$service) {
            throw new \Exception('Service non fourni');
        }

        $volumeHoraireListe = new VolumeHoraireListe($service);
        $vhlph              = new ListeFilterHydrator();
        $vhlph->setEntityManager($this->em());
        $vhlph->hydrate($this->params()->fromQuery() + $this->params()->fromPost(), $volumeHoraireListe);
        $service->setTypeVolumeHoraire($volumeHoraireListe->getTypeVolumeHoraire());

        $canViewMNP = $this->isAllowed($service->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_VISUALISATION);
        $canEditMNP = $canViewMNP && $this->isAllowed($service->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_EDITION);

        $hDeb = $volumeHoraireListe->getHeures();

        $form->setViewMNP($canViewMNP);
        $form->setEditMNP($canEditMNP);
        $form->build();
        $form->bindRequestSave($volumeHoraireListe, $this->getRequest(), function (VolumeHoraireListe $vhl) use ($hDeb, $volumeHoraireListe) {
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
            'formule', 'validation_enseignement', 'service', 'service_saisie', 'piece_jointe_fournie',
        ], $intervenant);
    }

}