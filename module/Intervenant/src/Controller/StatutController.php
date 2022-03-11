<?php

namespace Intervenant\Controller;

use Application\Cache\Traits\CacheContainerTrait;
use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Statut;
use Application\Provider\Privilege\Privileges;
use Intervenant\Form\StatutSaisieFormAwareTrait;
use Application\Provider\Role\RoleProvider;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Laminas\View\Model\ViewModel;

class StatutController extends AbstractController
{
    use StatutServiceAwareTrait;

    use StatutSaisieFormAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use CacheContainerTrait;
    use DossierAutreServiceAwareTrait;
    use ContextServiceAwareTrait;
    use PlafondServiceAwareTrait;

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

        $plafonds = $this->getServicePlafond()->getPlafondsConfig($statut);

        $canEdit = $this->isAllowed($statut, Privileges::INTERVENANT_STATUT_EDITION);
        if ($canEdit) {
            $form->bindRequestSave($statut, $this->getRequest(), function (Statut $si) use ($plafonds) {
                try {
                    $isNew = !$si->getId();
                    $this->getServiceStatut()->save($si);
                    foreach ($plafonds as $plafond) {
                        $this->getServicePlafond()->saveConfig($plafond);
                    }
                    unset($this->getCacheContainer(RoleProvider::class)->statutsInfo);
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                    if ($isNew) {
                        $this->redirect()->toRoute('statut/saisie', ['statut' => $si->getId()]);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            });
        } else {
            $form->bind($statut);
            $form->readOnly();
        }

        return compact('typesIntervenants', 'canEdit', 'statut', 'statuts', 'form', 'title', 'plafonds');
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

