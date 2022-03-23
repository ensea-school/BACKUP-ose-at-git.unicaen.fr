<?php

namespace Application\Controller;

use Application\Entity\Db\Grade;
use Application\Form\Grade\Traits\GradeSaisieFormAwareTrait;
use Application\Service\Traits\GradeServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class GradeController extends AbstractController
{

    use GradeServiceAwareTrait;
    use GradeSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Grade::class,
        ]);

        $grades = $this->getServiceGrade()->getList();

        return compact('grades');
    }



    public function saisieAction()
    {
        $grade = $this->getEvent()->getParam('grade');

        $form = $this->getFormGradeGradeSaisie();

        if (empty($grade)) {
            $title = "Création d'un nouveau grade";
            $grade = $this->getServiceGrade()->newEntity();
        } else {
            $title = "Edition d'un grade";
        }

        $form->bindRequestSave($grade, $this->getRequest(), function (Grade $v) {
            try {
                $this->getServiceGrade()->save($v);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {

        $grade = $this->getEvent()->getParam('grade');
        try {
            $this->getServiceGrade()->delete($grade);

            $this->flashMessenger()->addSuccessMessage("Grade supprimé avec succés");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('grade'));
    }
}