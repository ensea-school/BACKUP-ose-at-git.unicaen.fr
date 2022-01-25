<?php

namespace Intervenant\Controller;

use Application\Cache\Traits\CacheContainerTrait;
use Intervenant\Entity\Db\Statut;
use Application\Provider\Privilege\Privileges;
use Intervenant\Form\StatutSaisieFormAwareTrait;
use Application\Provider\Role\RoleProvider;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Laminas\View\Model\ViewModel;

class StatutController extends AbstractController
{
    use StatutServiceAwareTrait;
    use StatutSaisieFormAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use CacheContainerTrait;
    use DossierAutreServiceAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Statut::class,
        ]);

        $statutsIntervenants = $this->getServiceStatut()->getList();

        return compact('statutsIntervenants');
    }



    public function saisieAction()
    {
        try {


            $statut       = $this->getEvent()->getParam('statut');
            $form         = $this->getFormStatutSaisie();
            $champsAutres = $this->getServiceDossierAutre()->getList();
            if (empty($statut)) {
                $title  = 'Création d\'un nouveau statut d\'intervenant';
                $statut = $this->getServiceStatut()->newEntity();
                $statut->setOrdre($this->getServiceStatut()->fetchMaxOrdre() + 1);
            } else {
                $title = 'Édition d\'un statut d\'intervenant';
            }

            $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_STATUT_EDITION));
            if ($canEdit) {
                $form->bindRequestSave($statut, $this->getRequest(), function (Statut $si) {
                    try {
                        $this->getServiceStatut()->save($si);
                        unset($this->getCacheContainer(RoleProvider::class)->statutsInfo);
                        unset($this->getCacheContainer(PrivilegeService::class)->privilegesRoles);
                        $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($this->translate($e));
                    }
                });
            } else {
                $form->bind($statut);
                $form->readOnly();
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }


        return compact('form', 'title', 'champsAutres');
    }



    public function cloneAction()
    {
        /* @var $statut Statut */
        $statut    = $this->getEvent()->getParam('statut');
        $newStatut = $statut->dupliquer();
        $newStatut->setOrdre($this->getServiceStatut()->fetchMaxOrdre() + 1);
        $form         = $this->getFormStatutSaisie();
        $title        = 'Duplication d\'un statut d\'intervenant';
        $champsAutres = $this->getServiceDossierAutre()->getList();

        $form->bindRequestSave($newStatut, $this->getRequest(), function (Statut $si) {
            try {
                $this->getServiceStatut()->save($si);
                unset($this->getCacheContainer(RoleProvider::class)->statutsInfo);
                unset($this->getCacheContainer(PrivilegeService::class)->privilegesRoles);
                $this->flashMessenger()->addSuccessMessage('Duplication effectuée');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        $viewModel = new ViewModel();
        $viewModel->setVariables(compact('form', 'title'));
        $viewModel->setTemplate('application/statut/saisie');

        return $viewModel;
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
                unset($this->getCacheContainer(RoleProvider::class)->statutsInfo);
                unset($this->getCacheContainer(PrivilegeService::class)->privilegesRoles);
                $this->flashMessenger()->addSuccessMessage("Statut d'Intervenant supprimé avec succès.");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        return new MessengerViewModel(compact('statut'));
    }



    public function trierAction()
    {
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $ordre     = $this->getServiceStatut()->fetchMaxOrdre() + 1; // Pour éviter tout doublon!!
        foreach ($champsIds as $champId) {
            $si = $this->getServiceStatut()->get($champId);
            if ($si) {
                $si->setOrdre($ordre);
                $ordre++;
                try {
                    $this->getServiceStatut()->save($si);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        }

        return new JsonModel(['msg' => 'Tri des champs effectué']);
    }

}

