<?php

namespace Intervenant\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Framework\Cache\CacheContainerTrait;
use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Intervenant\Entity\Db\Statut;
use Intervenant\Form\StatutSaisieForm;
use Intervenant\Form\StatutSaisieFormAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Laminas\View\Model\ViewModel;
use Plafond\Form\PlafondConfigFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class StatutController extends AbstractController
{
    use StatutServiceAwareTrait;

    use StatutSaisieFormAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use CacheContainerTrait;
    use PlafondConfigFormAwareTrait;
    use ParametresServiceAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Statut::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            Statut::class,
        ]);

        $typesIntervenants = $this->getServiceTypeIntervenant()->getList();
        $statuts           = $this->getServiceStatut()->getList();

        return compact('typesIntervenants', 'statuts');
    }



    public function saisieAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Statut::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            Statut::class,
        ]);

        $typesIntervenants = $this->getServiceTypeIntervenant()->getList();
        $statuts           = $this->getServiceStatut()->getList();


        /** @var Statut $statut */
        $statut = $this->getEvent()->getParam('statut');

        $form = $this->getFormStatutSaisie();

        if (empty($statut)) {
            $title  = 'Ajout d\'un nouveau statut';
            $statut = $this->getServiceStatut()->newEntity();
        } else {
            $title = $statut->getLibelle();
        }

        $canEdit = $this->isAllowed($statut, Privileges::INTERVENANT_STATUT_EDITION);
        if ($canEdit) {
            $request = $this->getRequest();
            $form->bindRequestSave($statut, $request, function(Statut $si) use ($request){
                $isNew = !$si->getId();
                $this->getServiceStatut()->save($si);
                $this->getFormPlafondConfig()->requestSaveConfigs($si, $request);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                if ($isNew) {
                    $this->redirect()->toRoute('statut/saisie', ['statut' => $si->getId()]);
                }
            });
        } else {
            $form->bind($statut);
            $elements = $form->getElements();
            $elements = array_keys($elements);
            $form->readOnly(true, $elements);
        }

        $signatureActivated = (!empty($this->getServiceParametres()->get('signature_electronique_parapheur'))) ? true : false;

        $vm = new ViewModel();
        $vm->setTemplate('intervenant/statut/saisie');
        $vm->setVariables(compact('typesIntervenants', 'canEdit', 'statut', 'statuts', 'form', 'title', 'signatureActivated'));

        return $vm;
    }



    public function dupliquerAction()
    {
        $vm = $this->saisieAction();

        /** @var StatutSaisieForm $form */
        $form = $vm->getVariable('form');
        $form->setAttribute('action', $this->url()->fromRoute('statut/saisie'));
        $form->get('code')->setValue(null);
        $form->get('libelle')->setValue($form->get('libelle')->getValue() . ' (Copie)');

        return $vm;
    }



    public function deleteAction()
    {
        $statut = $this->getEvent()->getParam('statut');

        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_STATUT_EDITION));

        if (!$canEdit) {
            $this->flashMessenger()->addErrorMessage('Statut non modifiable : droit non accordé, car vous n\'avez pas le privilège pour cela ou bien le statut est synchronisé depuis un autre logiciel');
        } else {
            try {
                $this->getServiceStatut()->delete($statut);
                $this->flashMessenger()->addSuccessMessage("Statut d'Intervenant supprimé avec succès.");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return new MessengerViewModel(compact('statut'));
    }



    public function trierAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Statut::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            Statut::class,
        ]);

        $statuts = $this->getServiceStatut()->getList();
        $ids     = $this->params()->fromPost('ids');

        $ordre = $this->getServiceStatut()->fetchMaxOrdre() + 1; // Pour éviter tout doublon!!

        foreach ($ids as $id) {
            $statut = $statuts[$id] ?? null;
            if ($statut) {
                $statut->setOrdre($ordre++);
                try {
                    $this->getServiceStatut()->save($statut);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        }
        if (!$this->flashMessenger()->hasErrorMessages()) {
            $this->flashMessenger()->addSuccessMessage('Le tri a été pris en compte');
        }

        return new MessengerViewModel();
    }

}

