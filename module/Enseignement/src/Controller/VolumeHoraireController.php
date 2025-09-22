<?php

namespace Enseignement\Controller;

use Application\Controller\AbstractController;
use Application\Form\AbstractForm;
use Application\Provider\Privilege\Privileges;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Enseignement\Entity\VolumeHoraireListe;
use Enseignement\Form\VolumeHoraireSaisieCalendaireFormAwareTrait;
use Enseignement\Form\VolumeHoraireSaisieForm;
use Enseignement\Form\VolumeHoraireSaisieFormAwareTrait;
use Enseignement\Hydrator\ListeFilterHydrator;
use Enseignement\Service\ServiceServiceAwareTrait;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Paiement\Entity\Db\MotifNonPaiement;
use Plafond\Processus\PlafondProcessusAwareTrait;
use RuntimeException;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Workflow\Service\WorkflowServiceAwareTrait;

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
        $intervenant = $service->getIntervenant();
        $readOnly    = 1 == (int)$this->params()->fromQuery('read-only', 0);

        $volumeHoraireListe = $service->getVolumeHoraireListe()->setTypeVolumehoraire($typeVolumeHoraire);
        $semestriel         = $intervenant->getStatut()->isModeEnseignementSemestriel($typeVolumeHoraire);


        return compact('volumeHoraireListe', 'readOnly', 'semestriel');
    }



    public function saisieAction()
    {
        return $this->saisieMixte($this->getFormVolumeHoraireSaisie());
    }



    public function saisieCalendaireAction()
    {
        /**
         * @var Service $service
         */
        $service = $this->getEvent()->getParam('service');
        $form    = $this->getFormVolumeHoraireSaisieCalendaire();
        $form->setElementPedagogique($service->getElementPedagogique());

        return $this->saisieMixte($form);
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
        //Si le volume horaire est validé on bloque la modification de motif de non paiement
        $vhs = $volumeHoraireListe->getVolumeHoraires();
        foreach ($vhs as $vh) {
            if ($vh->isValide()) {
                $form->disableMotifNonPaiement();
                break;
            }
        }

        $bind = $form->bindRequestSave($volumeHoraireListe, $this->getRequest(), function (VolumeHoraireListe $vhl) use ($hDeb, $volumeHoraireListe) {
            try {
                $service = $vhl->getService();
                $this->getProcessusPlafond()->beginTransaction();
                $this->getServiceService()->save($service);
                $hFin = $volumeHoraireListe->getHeures();
                $this->updateTableauxBord($service->getIntervenant());
                if (!$this->getProcessusPlafond()->endTransaction($service, $vhl->getTypeVolumeHoraire(), $hFin < $hDeb)) {
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
            $this->getProcessusPlafond()->endTransaction($service, $volumeHoraireListe->getTypeVolumeHoraire(), true);
            $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            TblProvider::FORMULE, TblProvider::VALIDATION_ENSEIGNEMENT, TblProvider::SERVICE, TblProvider::PIECE_JOINTE,
        ], $intervenant);
    }

}