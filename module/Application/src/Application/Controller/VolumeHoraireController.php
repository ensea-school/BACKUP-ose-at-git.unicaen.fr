<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\VolumeHoraireListe;
use Application\Filter\StringFromFloat;
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
use Application\Exception\DbException;

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
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\MotifNonPaiement::class,
        ]);

        /** @var Service $service */
        $service           = $this->context()->serviceFromRoute();
        $volumeHoraireList = $service->getVolumeHoraireListe();
        $vhlfh             = new ListeFilterHydrator();
        $vhlfh
            ->setEntityManager($this->em())
            ->hydrate($this->params()->fromQuery() + $this->params()->fromPost(), $volumeHoraireList);
        $service->setTypeVolumeHoraire($volumeHoraireList->getTypeVolumeHoraire());

        $canViewMNP = $this->isAllowed($service->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_VISUALISATION);
        $canEditMNP = $this->isAllowed($service->getIntervenant(), Privileges::MOTIF_NON_PAIEMENT_EDITION);

        if ($canViewMNP) {
            $ancienMotifNonPaiement = $this->context()->motifNonPaiementFromQueryPost('ancien-motif-non-paiement', $volumeHoraireList->getMotifNonPaiement());
        } else {
            $ancienMotifNonPaiement = false;
        }

        $form = $this->getFormVolumeHoraireSaisie();
        $form->setViewMNP($canViewMNP);
        $form->setEditMNP($canEditMNP);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $heures = StringFromFloat::run($request->getPost()['heures']);
            try {
                $volumeHoraireList->setHeures($heures, $volumeHoraireList->getMotifNonPaiement(), $ancienMotifNonPaiement);
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }
        $form->bind($volumeHoraireList);
        if ($request->isPost()) {
            if ($form->isValid()) {
                try {
                    $this->getProcessusPlafond()->beginTransaction();
                    $this->getServiceService()->save($service);
                    $this->updateTableauxBord($service->getIntervenant());
                    $this->getProcessusPlafond()->endTransaction($service->getIntervenant(), $volumeHoraireList->getTypeVolumeHoraire());
                } catch (\Exception $e) {
                    $e = DbException::translate($e);
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
            } else {
                $this->flashMessenger()->addErrorMessage('La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.');
            }
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
            ->setTemplate('application/volume-horaire/saisie')
            ->setVariables(compact('form', 'ancienMotifNonPaiement'));

        return $viewModel;
    }



    public function saisieCalendaireAction()
    {
        /** @var Service $service */
        $service = $this->getEvent()->getParam('service');

        if (!$service) {
            throw new \Exception('Service non fourni');
        }

        $volumeHoraireListe = new VolumeHoraireListe($service);
        $vhlph              = new ListeFilterHydrator();
        $vhlph->setEntityManager($this->em());
        $vhlph->hydrate($this->params()->fromQuery() + $this->params()->fromPost(), $volumeHoraireListe);

        $form  = $this->getFormVolumeHoraireSaisieCalendaire();
        $title = "Modification d'une ligne de service";

        return compact('title', 'form');
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule', 'validation_enseignement', 'service', 'service_saisie', 'piece_jointe_fournie',
        ], $intervenant);
    }

}