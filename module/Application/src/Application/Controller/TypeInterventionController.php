<?php
namespace Application\Controller;

use Application\Service\Traits\TypeInterventionStructureServiceAwareTrait;
use Application\Service\Traits\TypeInterventionAwareTrait;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeInterventionStructure;
use Application\Form\TypeIntervention\Traits\TypeInterventionSaisieFormAwareTrait;
use Application\Form\TypeIntervention\Traits\TypeInterventionStructureSaisieFormAwareTrait;
use Application\Exception\DbException;
use UnicaenApp\View\Model\MessengerViewModel;

class TypeInterventionController extends AbstractController
{
    use TypeInterventionAwareTrait;
    use TypeInterventionStructureServiceAwareTrait;
    use TypeInterventionSaisieFormAwareTrait;
    use TypeInterventionStructureSaisieFormAwareTrait;



    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeIntervention::class,
        ]);

        $typesInterventions = $this->getServiceTypeIntervention()->getList();

        $this->em()->getFilters()->enable('historique')->init([
            TypeInterventionStructure::class,
        ]);
        $typesInterventionsStructures = $this->getServiceTypeInterventionStructure()->getList();

        return compact('typesInterventions', 'typesInterventionsStructures');
    }



    public function saisieAction()
    {
        /* @var $typeIntervention TypeIntervention */

        $typeIntervention = $this->getEvent()->getParam('typeIntervention');
        $form             = $this->getFormTypeInterventionSaisie();
        if (empty($typeIntervention)) {
            $title            = 'Création d\'un nouveau type d\'intervention';
            $typeIntervention = $this->getServiceTypeIntervention()->newEntity();
        } else {
            $title = 'Édition d\'un type d\'intervention';
        }

        $form->bindRequestSave($typeIntervention, $this->getRequest(), function (TypeIntervention $ti) {
            try {
                $this->getServiceTypeIntervention()->save($ti);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $ti->getId());
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        /* @var $typeIntervention TypeIntervention */
        $typeIntervention = $this->getEvent()->getParam('typeIntervention');

        try {
            $this->getServiceTypeIntervention()->delete($typeIntervention);
            $this->flashMessenger()->addSuccessMessage("Type d\'intervention supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel(compact('typeIntervention'));
    }



    public function typeInterventionStructureSaisieAction()
    {
        /* @var $typeInterventionStructure TypeInterventionStructure */

        $typeIntervention          = $this->getEvent()->getParam('typeIntervention');
        $typeInterventionStructure = $this->getEvent()->getParam('typeInterventionStructure');
        $form                      = $this->getFormTypeInterventionStructureSaisie();
        if (empty($typeInterventionStructure)) {
            $title                     = 'Création d\'un type d\'intervention pour une structure';
            $typeInterventionStructure = $this->getServiceTypeInterventionStructure()->newEntity()
                ->setTypeIntervention($typeIntervention);
        } else {
            $title = 'Édition d\'un type d\'intervention pour une structure';
        }
        $form->bindRequestSave($typeInterventionStructure, $this->getRequest(), function (TypeInterventionStructure $tis) {
            try {
                $this->getServiceTypeInterventionStructure()->save($tis);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $tis->getId());
            }
        });

        return compact('form', 'title');
    }



    public function typeInterventionStructureDeleteAction()
    {
        /* @var $typeInterventionStructure TypeInterventionStructure */
        $typeInterventionStructure = $this->getEvent()->getParam('typeInterventionStructure');

        try {
            $this->getServiceTypeInterventionStructure()->delete($typeInterventionStructure);
            $this->flashMessenger()->addSuccessMessage("Type d\'intervention pour une structure supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel(compact('typeInterventionStructure'));
    }

}
