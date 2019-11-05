<?php

namespace Application\Controller;

use Application\Entity\Db\StatutIntervenant;
use Application\Provider\Privilege\Privileges;
use Application\Form\StatutIntervenant\Traits\StatutIntervenantSaisieFormAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Zend\View\Model\ViewModel;

class StatutIntervenantController extends AbstractController
{
    use StatutIntervenantServiceAwareTrait;
    use StatutIntervenantSaisieFormAwareTrait;
    use TypeIntervenantServiceAwareTrait;



    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            StatutIntervenant::class,
        ]);

        $statutsIntervenants = $this->getServiceStatutIntervenant()->getList();

        return compact('statutsIntervenants');
    }



    public function saisieAction()
    {
        /* @var $statutIntervenant StatutIntervenant */

        $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');
        $form              = $this->getFormStatutIntervenantSaisie();
        if (empty($statutIntervenant)) {
            $title             = 'Création d\'un nouveau statut d\'intervenant';
            $statutIntervenant = $this->getServiceStatutIntervenant()->newEntity();
            $statutIntervenant->setOrdre($this->getServiceStatutIntervenant()->fetchMaxOrdre() + 1);
        } else {
            $title = 'Édition d\'un statut d\'intervenant';
        }

        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_STATUT_EDITION));
        if ($statutIntervenant->getSource()->getImportable()) {
            $canEdit = false; // Si la source est synchronisable alors pas d'édition possible
        }

        if ($canEdit) {
            $form->bindRequestSave($statutIntervenant, $this->getRequest(), function (StatutIntervenant $si) {
                try {
                    $this->getServiceStatutIntervenant()->save($si);
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            });
        } else {
            $form->bind($statutIntervenant);
            $form->readOnly();
        }


        return compact('form', 'title');
    }



    public function cloneAction()
    {
        /* @var $statutIntervenant StatutIntervenant */
        $statutIntervenant    = $this->getEvent()->getParam('statutIntervenant');
        $newStatutIntervenant = $statutIntervenant->dupliquer();
        $newStatutIntervenant->setOrdre($this->getServiceStatutIntervenant()->fetchMaxOrdre() + 1);
        $form                 = $this->getFormStatutIntervenantSaisie();
        $title                = 'Duplication d\'un statut d\'intervenant';

        $form->bindRequestSave($newStatutIntervenant, $this->getRequest(), function (StatutIntervenant $si) {
            try {
                $this->getServiceStatutIntervenant()->save($si);
                $this->flashMessenger()->addSuccessMessage('Duplication effectuée');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        $viewModel = new ViewModel();
        $viewModel->setVariables(compact('form', 'title'));
        $viewModel->setTemplate('application/statut-intervenant/saisie');

        return $viewModel;
    }



    public function deleteAction()
    {
        $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');

        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_STATUT_EDITION));
        if ($statutIntervenant->getSource()->getImportable()) {
            $canEdit = false; // Si la source est synchronisable alors pas d'édition possible
        }

        if (!$canEdit) {
            $this->flashMessenger()->addErrorMessage('Statut non modifiable : droit non accordé, car vous n\'avez pas le privilège pour cela ou bien le statut est synchronisé depuis un autre logiciel');
        } else {
            try {
                $this->getServiceStatutIntervenant()->delete($statutIntervenant);
                $this->flashMessenger()->addSuccessMessage("Statut d'Intervenant supprimé avec succès.");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return new MessengerViewModel(compact('statutIntervenant'));
    }



    public function statutIntervenantTrierAction()
    {
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $ordre     = $this->getServiceStatutIntervenant()->fetchMaxOrdre() + 1; // Pour éviter tout doublon!!
        foreach ($champsIds as $champId) {
            $si = $this->getServiceStatutIntervenant()->get($champId);
            if ($si) {
                $si->setOrdre($ordre);
                $ordre++;
                try {
                    $this->getServiceStatutIntervenant()->save($si);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        }

        return new JsonModel(['msg' => 'Tri des champs effectué']);
    }

}

