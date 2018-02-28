<?php

namespace Application\Controller;
use Application\Entity\Db\Modulateur;
use Application\Form\Modulateur\Traits\TypeModulateurStrctureSaisieFormAwareTrait;
use Application\Service\Traits\ModulateurServiceAwareTrait;
use Application\Entity\Db\TypeModulateurStructure;
use Application\Service\Traits\TypeModulateurStructureServiceAwareTrait;
use Application\Entity\Db\TypeModulateur;
use Application\Exception\DbException;
use Application\Form\Modulateur\Traits\ModulateurSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Service\Traits\TypeModulateurServiceAwareTrait;
use Application\Form\Modulateur\Traits\TypeModulateurSaisieFormAwareTrait;
use Application\Form\Modulateur\Traits\TypeModulateurStructureSaisieFormAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;

class modulateurController extends AbstractController
{
    use ModulateurServiceAwareTrait;
    use TypeModulateurServiceAwareTrait;
    use ModulateurSaisieFormAwareTrait;
    use TypeModulateurSaisieFormAwareTrait;
    use TypeModulateurStructureSaisieFormAwareTrait;
    use TypeModulateurStructureServiceAwareTrait;
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;



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
        $modulateurs     = $this->getServiceModulateur()->getList();
        $typeModulateurs = $this->getServiceTypeModulateur()->getList();
        $typeModulateurStructures = $this->getServiceTypeModulateurStructure()->getList();


        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $structure   = $role->getStructure();

        return compact('modulateurs', 'typeModulateurs','typeModulateurStructures','structure');
    }



    public function saisieAction()
    {
        /* @var $modulateur modulateur */
        /* @var $typeModulateur typeModulateur */

        $modulateur     = $this->getEvent()->getParam('modulateur');
        $typeModulateur = $this->getEvent()->getParam('typeModulateur');
        $form           = $this->getFormModulateurSaisie();
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
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $modu->getId());
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
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel(compact('modulateur'));
    }



    public function typeModulateurSaisieAction()
    {
        /* @var $typeModulateur typeModulateur */
        $typeModulateur = $this->getEvent()->getParam('typeModulateur');

        $form = $this->getFormTypeModulateurSaisie();
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
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $tm->getId());
            }
        });

        return compact('form', 'title');
    }

    public function typeModulateurStructureSaisieAction(){
        /* @var $typeModulateur typeModulateur */
        /* @var $typeModulateurStructure typeModulateurStructure */
        $typeModulateur = $this->getEvent()->getParam('typeModulateur');
        $typeModulateurStructure = $this->getEvent()->getParam('typeModulateurStructure');

        $form = $this->getFormTypeModulateurStructureSaisie();
        if (empty($typeModulateurStructure)) {
            $title          = 'Création d\'un nouveau Type de Modulateur';
            $typeModulateurStructure = $this->getServiceTypeModulateurStructure()->newEntity();
            $typeModulateurStructure->setTypeModulateur($typeModulateur);
        } else {
            $title = 'Édition d\'une structure d\'un Type de Modulateur';
        }

        $form->bindRequestSave($typeModulateurStructure, $this->getRequest(), function (TypeModulateurStructure $tms) {
            try {
                $this->getServiceTypeModulateurStructure()->save($tms);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $tms->getId());
            }
        });

        return compact('form', 'title');
    }

    public function typeModulateurStructureDeleteAction(){
        $typeModulateurStructure = $this->getEvent()->getParam('typeModulateurStructure');
        try {
            $this->getServiceTypeModulateurStructure()->delete($typeModulateurStructure);
            $this->flashMessenger()->addSuccessMessage("Structure supprimée avec succès pour ce type de modulateur.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }
        return new MessengerViewModel(compact('typeModulateurStructure'));
    }

    public function typeModulateurDeleteAction()
    {
        /* @var $typeModulateur typeModulateur */
        $typeModulateur = $this->getEvent()->getParam('typeModulateur');
        try {
            $this->getServiceTypeModulateur()->delete($typeModulateur);
            $this->flashMessenger()->addSuccessMessage("Type de Modulateur supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel(compact('typeModulateur'));
    }
}