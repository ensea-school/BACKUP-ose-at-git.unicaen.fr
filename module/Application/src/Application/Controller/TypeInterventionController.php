<?php
namespace Application\Controller;
use TypeInterventionAwareTrait;
use Application\Entity\Db\TypeIntervention;
use Application\Form\TypeIntervention\Traits\TypeInterventionSaisieFormAwareTrait;
use Application\Exception\DbException;
use UnicaenApp\View\Model\MessengerViewModel;

class TypeInterventionController extends AbstractController
{
    use \Application\Service\Traits\TypeInterventionAwareTrait;
    use TypeInterventionSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeIntervention::class,
        ]);

        $typesInterventions = $this->getServiceTypeIntervention()->getList();

        return compact('typesInterventions');
    }


    public function saisieAction()
    {
        /* @var $typeIntervention TypeIntervention */

        $typeIntervention = $this->getEvent()->getParam('typeIntervention');
        $form = $this->getFormTypeInterventionSaisie();
        if (empty($typeIntervention)) {
            $title = 'Création d\'un nouveau type d\'intervention';
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
}
