<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Paiement\Entity\Db\Modulateur;
use Paiement\Entity\Db\TypeModulateur;
use Paiement\Entity\Db\TypeModulateurStructure;
use Paiement\Form\Modulateur\ModulateurSaisieFormAwareTrait;
use Paiement\Form\Modulateur\TypeModulateurSaisieFormAwareTrait;
use Paiement\Form\Modulateur\TypeModulateurStructureSaisieFormAwareTrait;
use Paiement\Service\ModulateurServiceAwareTrait;
use Paiement\Service\TypeModulateurServiceAwareTrait;
use Paiement\Service\TypeModulateurStructureServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class ModulateurController extends AbstractController
{
    use ModulateurServiceAwareTrait;
    use TypeModulateurServiceAwareTrait;
    use ModulateurSaisieFormAwareTrait;
    use TypeModulateurSaisieFormAwareTrait;
    use TypeModulateurStructureServiceAwareTrait;
    use TypeModulateurStructureSaisieFormAwareTrait;
    use ContextServiceAwareTrait;


    public function indexAction()
    {
        /* @var $modulateurs [] modulateur */
        /* @var $typeModulateurs [] typeModulateur */
        /* @var $typeModulateurStructures [] typeModulateurStructure */
        /* @var $TMD typeModulateurStructure */
        $this->em()->getFilters()->enable('historique')->init([
            Modulateur::class,
        ]);
        $this->em()->getFilters()->enable('historique')->init([
            TypeModulateur::class,
        ]);
        $this->em()->getFilters()->enable('historique')->init([
            TypeModulateurStructure::class,
        ]);
        $anneeId                  = $this->getServiceContext()->getAnnee()->getId();
        $modulateurs              = $this->getServiceModulateur()->getList();
        $typeModulateurs          = $this->getServiceTypeModulateur()->getList();
        $typeModulateurStructures = $this->getServiceTypeModulateurStructure()->getList();

        $structure = $this->getServiceContext()->getStructure();

        return compact('modulateurs', 'typeModulateurs', 'typeModulateurStructures', 'structure', 'anneeId');
    }



    public function saisieAction()
    {
        /* @var $modulateur modulateur */
        /* @var $typeModulateur typeModulateur */

        $modulateur     = $this->getEvent()->getParam('modulateur');
        $typeModulateur = $this->getEvent()->getParam('typeModulateur');
        $form           = $this->getFormModulateurModulateurSaisie();
        if (empty($modulateur)) {
            $title      = 'Création d\'un nouveau Modulateur';
            $modulateur = $this->getServicemodulateur()->newEntity()
                ->setTypeModulateur($typeModulateur);
        } else {
            $title = 'Édition d\'un Modulateur';
        }

        $form->bindRequestSave($modulateur, $this->getRequest(), function (modulateur $modu) {
            try {
                $this->getServiceModulateur()->save($modu);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        $modulateur = $this->getEvent()->getParam('modulateur');
        try {
            $this->getServiceModulateur()->delete($modulateur);
            $this->flashMessenger()->addSuccessMessage("Modulateur supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('modulateur'));
    }



    public function typeModulateurSaisieAction()
    {
        /* @var $typeModulateur typeModulateur */
        $typeModulateur = $this->getEvent()->getParam('typeModulateur');

        $form = $this->getFormModulateurTypeModulateurSaisie();
        if (empty($typeModulateur)) {
            $title          = 'Création d\'un nouveau Type de Modulateur';
            $typeModulateur = $this->getServiceTypeModulateur()->newEntity();
        } else {
            $title = 'Édition d\'un Type de Modulateur';
        }

        $form->bindRequestSave($typeModulateur, $this->getRequest(), function (typeModulateur $tm) {
            try {
                $this->getServiceTypeModulateur()->save($tm);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function typeModulateurDeleteAction()
    {
        /* @var $typeModulateur typeModulateur */
        $typeModulateur = $this->getEvent()->getParam('typeModulateur');
        try {
            $this->getServiceTypeModulateur()->delete($typeModulateur);
            $this->flashMessenger()->addSuccessMessage("Type de Modulateur supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('typeModulateur'));
    }



    public function typeModulateurStructureSaisieAction()
    {
        /* @var $typeModulateurStructure typeModulateurStructure */
        /* @var $typeModulateur typeModulateur */
        $typeModulateur          = $this->getEvent()->getParam('typeModulateur');
        $typeModulateurStructure = $this->getEvent()->getParam('typeModulateurStructure');

        $form = $this->getFormModulateurTypeModulateurStructureSaisie();
        if (empty($typeModulateurStructure)) {
            $title                   = 'Ajout d\'une structure pour le type de modulateur';
            $typeModulateurStructure = $this->getServiceTypeModulateurStructure()->newEntity()
                ->setTypeModulateur($typeModulateur);
        } else {
            $title = 'Édition d\'une structure pour le type de modulateur';
        }

        $form->bindRequestSave($typeModulateurStructure, $this->getRequest(), function (typeModulateurStructure $tms) {
            try {
                $this->getServiceTypeModulateurStructure()->save($tms);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function typeModulateurStructureDeleteAction()
    {
        /* @var $typeModulateurStructure typeModulateurStructure */
        $typeModulateurStructure = $this->getEvent()->getParam('typeModulateurStructure');
        try {
            $this->getServiceTypeModulateurStructure()->delete($typeModulateurStructure);
            $this->flashMessenger()->addSuccessMessage("Type de Modulateur de structure supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }
}